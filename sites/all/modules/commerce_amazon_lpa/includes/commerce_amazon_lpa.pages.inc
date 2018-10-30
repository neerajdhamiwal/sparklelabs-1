<?php

/**
 * @file
 * Various page callbacks.
 */

/**
 * Pay with Amazon page callback.
 *
 * This is the page callback used by the Pay With Amazon link. This sets a
 * flag on the order to alert the rest of the module this will use Amazon as its
 * checkout method.
 *
 * @return int|array
 *   MENU_* Value that the page manager will use to set the status.
 */
function commerce_amazon_lpa_checkout() {
  if (variable_get('commerce_amazon_lpa_popup', 'popup') != 'popup' && !isset($_COOKIE['amazon_Login_accessToken'])) {
    return array(
      '#attached' => array(
        'library' => array(array('commerce_amazon_lpa', 'amazon_widgets')),
        'js' => array(
          drupal_get_path('module', 'commerce_amazon_lpa') . '/js/commerce-amazon-lpa-redirect.js',
        ),
      ),
    );
  }

  $order = commerce_cart_order_load($GLOBALS['user']->uid);
  // If the user does not have access to checkout the order, return a 404. We
  // could return a 403, but then the user would know they've identified a
  // potentially valid checkout URL.
  $user_info = AmazonLPA::instance()->getUserInfo();
  if (!empty($order) && !commerce_checkout_access($order)|| empty($user_info)) {
    return MENU_ACCESS_DENIED;
  }

  // Ensure user is logged in. If not, do so on their behalf.
  if (AmazonLPA::get_operation_mode() == AmazonLPA::OPERATION_MODE_LOGIN_AND_PAY && !user_is_logged_in()) {
    commerce_amazon_lpa_external_login();
  }

  // If an order is being saved without a contract ID, assume it has just left
  // the cart and beginning life in checkout.
  // Set the account info for the order.
  $order->data['commerce_amazon_lpa']['user_info'] = $user_info;
  if (commerce_order_save($order)) {
    drupal_goto('checkout');
  }
  else {
    drupal_set_message(t('There was an error initiating the Amazon checkout, please try again'), 'error');
    drupal_goto('cart');
  }
}

/**
 * Page callback for handling IPN notifications.
 *
 * @throws \Exception
 *   Amazon Exception.
 */
function commerce_amazon_lpa_process_ipn() {
  libraries_load('login-and-pay-with-amazon-sdk-php');

  $headers = array();

  foreach ($_SERVER as $name => $value) {
    if (substr($name, 0, 5) == 'HTTP_') {
      $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
    }
  }

  $ipn_handler = new PayWithAmazon\IpnHandler($headers, file_get_contents('php://input'));
  $ipn_message = $ipn_handler->toArray();

  commerce_amazon_lpa_add_debug_log(t('Amazon IPN debug: !debug', array('!debug' => '<pre>' . print_r($ipn_message, TRUE) . '</pre>')));

  // Decide what to do based on the notification type. Amazon defines several
  // types of notifications. See link below. However, the notification types
  // they define don't appear in the actual responses. e.g.
  // AuthorizationNotification is defined in the docs, but PaymentAuthorize is
  // what actually gets sent with the IPN notification.
  // @see https://payments.amazon.com/documentation/apireference/201757720
  switch ($ipn_message['NotificationType']) {
    case 'OrderReferenceNotification':
      $data = $ipn_message['OrderReference'];
      $order = commerce_amazon_lpa_order_from_amazon_reference_id($data['AmazonOrderReferenceId']);

      if (empty($order)) {
        watchdog('commerce_amazon_lpa', 'Unable to find matching order for reference @id.', array('@id' => $data['AmazonOrderReferenceId']), WATCHDOG_ERROR);
        break;
      }

      $order->data['commerce_amazon_lpa']['order_reference'] = $data;

      switch ($data['OrderReferenceStatus']['State']) {
        case 'Canceled':
          commerce_order_status_update($order, 'canceled', TRUE, TRUE, t('Canceled by Amazon. Reason code: @code', array(
            '@code' => $data['OrderReferenceStatus']['ReasonCode'],
          )));
          break;

        case 'Suspended':
          commerce_order_status_update($order, $order->status, TRUE, TRUE, t('Suspended by Amazon. Reason code: @code', array(
            '@code' => $data['OrderReferenceStatus']['ReasonCode'],
          )));
          break;

        case 'Closed':
          commerce_order_status_update($order, 'completed', TRUE, TRUE, t('Closed notification by Amazon. Reason code: @code', array(
            '@code' => $data['OrderReferenceStatus']['ReasonCode'],
          )));
          break;
      }

      commerce_order_save($order);
      break;

    case 'PaymentAuthorize':
    case 'AuthorizationNotification':
      $data = $ipn_message['AuthorizationDetails'];

      $transaction = commerce_amazon_lpa_remote_payment_transaction_load($data['AmazonAuthorizationId']);
      if ($transaction) {
        AmazonLPA::instance()->processAuthorizeTransaction($transaction, $data);

        $order = commerce_order_load($transaction->order_id);
        if (!empty($order->data['commerce_amazon_lpa_set_as_auth'])) {
          $authorized_order_status = variable_get('commerce_amazon_lpa_auth_order_status', 'pending');
          commerce_order_status_update($order, $authorized_order_status, FALSE, TRUE, t('Payment with Amazon was authorized, moving to proper status.'));
          unset($order->data['commerce_amazon_lpa_set_as_auth']);
          commerce_order_save($order);
        }
      }
      else {
        watchdog('commerce_amazon_lpa', 'Unable to find matching payment transaction authorization for @id', array('@id' => $data['AmazonAuthorizationId']), WATCHDOG_ERROR);
      }
      break;

    case 'PaymentCapture':
    case 'CaptureNotification':
      $data = $ipn_message['CaptureDetails'];

      // Try to load the transaction first, the ID will have been updated if it
      // was captured / triggered in the UI.
      $transaction = commerce_amazon_lpa_remote_payment_transaction_load($data['AmazonCaptureId']);

      // If we have no transaction, we must look up the matching authorization
      // remote ID.
      if (!$transaction) {
        $id_components = explode('-', $data['AmazonCaptureId']);
        $id_components[3] = str_replace('C', 'A', $id_components[3]);
        $authorization_id = implode('-', $id_components);
        $transaction = commerce_amazon_lpa_remote_payment_transaction_load($authorization_id);
      }

      if ($transaction) {
        AmazonLPA::instance()->processCaptureTransaction($transaction, $data);
      }
      else {
        watchdog('commerce_amazon_lpa', 'Unable to find matching payment transaction capture for @id', array('@id' => $data['AmazonCaptureId']), WATCHDOG_ERROR);
      }

      break;

    case 'PaymentRefund':
    case 'RefundNotification':
      $data = $ipn_message['RefundDetails'];
      $transaction = commerce_amazon_lpa_remote_payment_transaction_load($data['AmazonRefundId']);
      if (!$transaction) {
        AmazonLPA::instance()->processRefundTransaction($transaction, $data);
      }
      else {
        watchdog('commerce_amazon_lpa', 'Unable to find matching payment transaction refund for @id', array('@id' => $data['AmazonRefundId']), WATCHDOG_ERROR);
      }
      break;

    default:
      commerce_amazon_lpa_add_debug_log('Amazon IPN debug: IPN case did not match for @type', array(
        '@type' => $ipn_message['NotificationType'],
      ));
      break;
  }

}

/**
 * Page callback for Amazon Login.
 *
 * Verifies the access token and logs the user in as an external user.
 */
function commerce_amazon_lpa_login_callback() {
  if (variable_get('commerce_amazon_lpa_popup', 'popup') != 'popup' && !isset($_COOKIE['amazon_Login_accessToken'])) {
    return array(
      '#attached' => array(
        'library' => array(array('commerce_amazon_lpa', 'amazon_widgets')),
        'js' => array(
          drupal_get_path('module', 'commerce_amazon_lpa') . '/js/commerce-amazon-lpa-redirect.js',
        ),
      ),
    );
  }
  else {
    commerce_amazon_lpa_external_login();
    drupal_goto('user');
  }
}
