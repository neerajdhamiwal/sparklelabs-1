<?php

/**
 * @file
 * Commerce Payment method info callbacks.
 */

/**
 * Payment method callback: submit form.
 */
function commerce_amazon_login_and_pay_submit_form($payment_method, $pane_values, $checkout_pane, $order) {
  $form['info'] = array(
    '#markup' => t('Complete the payment using Login and Pay by Amazon with the address and wallet selected in previous steps.'),
  );

  $wrapper = entity_metadata_wrapper('commerce_order', $order);
  $form['amazon_order_reference_id'] = array(
    '#type' => 'hidden',
    '#default_value' => $wrapper->{AmazonLPA::REFERENCE_ID_FIELD}->value(),
  );
  $form['wallet_widget'] = array(
    '#theme' => 'commerce_amazon_wallet_widget',
    '#display_mode' => path_is_admin(current_path()) ? 'read' : '',
    '#order_reference_id' => $wrapper->{AmazonLPA::REFERENCE_ID_FIELD}->value(),
  );
  return $form;
}

/**
 * Payment method callback: submit form validation.
 *
 * @see commerce_checkout_by_amazon_submit_form_validate()
 */
function commerce_amazon_login_and_pay_submit_form_validate($payment_method, $pane_form, $pane_values, $order, $form_parents = array()) {
  if (!isset($order->data['commerce_amazon_lpa'])) {
    form_set_error('commerce_payment', t('Please select a payment method to continue.'));
    drupal_set_message(t('Some information for proceeding with the payment might be missing, please review previous steps in order to complete at least address and wallet information.'), 'error');
    return FALSE;
  }

  // There should be no more validation error before purchasing with Amazon.
  if (form_get_errors()) {
    return FALSE;
  }
}

/**
 * Payment method callback: submit form submission.
 *
 * If an order is digital, the order reference will have been generated in the
 * submit form, we save it here.
 */
function commerce_amazon_login_and_pay_submit_form_submit($payment_method, $pane_form, $pane_values, $order, $charge) {
  /** @var EntityDrupalWrapper $order_wrapper */
  $order_wrapper = entity_metadata_wrapper('commerce_order', $order);

  if (!commerce_amazon_lpa_order_is_shippable($order)) {
    try {
      $order_wrapper->{AmazonLPA::REFERENCE_ID_FIELD} = $pane_values['amazon_order_reference_id'];
      $order->data['commerce_amazon_lpa']['order_reference'] = AmazonLPA::instance()->getOrderRef($order_wrapper);
      $order_wrapper->save();
    }
    catch (Exception $e) {
      drupal_set_message(t('There was an error setting the Amazon order reference'), 'error');
      return FALSE;
    }
  }
}

/**
 * Payment method callback; generation callback for the payment redirect form.
 *
 * This actually processes the order and transaction with Amazon Payments and
 * then moves forward with the checkout.
 */
function commerce_amazon_login_and_pay_redirect_form($form, &$form_state, $order, $payment_method) {
  /** @var EntityDrupalWrapper $order_wrapper */
  $order_wrapper = entity_metadata_wrapper('commerce_order', $order);

  // On error we will redirect user's back to the checkout page with the
  // commerce_payment pane. We load it here to
  $checkout_panes = commerce_checkout_panes();
  $commerce_payment_pane = $checkout_panes['commerce_payment'];

  // Process the transaction.
  try {
    $api = AmazonLPA::instance();
    $order_reference = $api->getOrderRef($order_wrapper);
    $amazon_order_state = $order_reference['OrderReferenceStatus']['State'];
    if ($amazon_order_state == 'Draft' || $amazon_order_state = 'Suspended') {

      // Tell Amazon we're going to process a transaction against the order.
      if ($amazon_order_state == 'Draft') {
        // Set the existing state of the order to the order.
        $api->setOrderRef($order_wrapper);
      }
      $api->confirmOrderRef($order_wrapper);
    }
    // If the Status isn't open, and it isn't Draft, then we can't do anything.
    elseif ($amazon_order_state != 'Open') {
      commerce_order_status_update($order, 'checkout_' . $commerce_payment_pane['page'], FALSE, NULL, t('There is an order validating your order with Amazon.'));
      drupal_goto(commerce_checkout_order_uri($order));
    }

    // We're in ERP mode and aren't doing any processing.
    if (AmazonLPA::is_erp_mode()) {
      commerce_payment_redirect_pane_next_page($order, t('Amazon Login and Pay operating in ERP mode, skipped payment.'));
      drupal_goto(commerce_checkout_order_uri($order));
    }

    // Create a transaction.
    $transaction = commerce_payment_transaction_new($payment_method['method_id'], $order->order_id);
    $transaction->instance_id = $payment_method['instance_id'];

    // If the authorization mode is manual, create a placeholder transaction.
    if (AmazonLPA::get_authorization_mode() == AmazonLPA::AUTH_MANUAL) {
      $transaction->status = COMMERCE_PAYMENT_STATUS_PENDING;
      $transaction->data['commerce_amazon_lpa']['environment'] = variable_get('commerce_amazon_lpa_environment', AmazonLPA::ENV_SANDBOX);
      $transaction->data['commerce_amazon_lpa']['transaction_type'] = 'authorization';
      $transaction->amount = $order_wrapper->commerce_order_total->amount->value();
      $transaction->currency_code = $order_wrapper->commerce_order_total->currency_code->value();
      commerce_payment_transaction_save($transaction);
      commerce_order_status_update($order, 'awaiting_auth', FALSE, TRUE, t('Amazon set to manual authorization, order placed in "Awaiting Authorization" status.'));
    }
    // Otherwise run an authorization to be done async or sync.
    else {
      // Authorize so we can manually capture or automatically when shipped.
      // If set to CAPTURE_AUTH_CAPTURE, then do capture with the authorization.
      $data = $api->authorize($order_wrapper, AmazonLPA::get_capture_mode() == AmazonLPA::CAPTURE_AUTH_CAPTURE ? 'true' : 'false');

      $api->processAuthorizeTransaction($transaction, $data);

      // Mark the payment as a failure.
      if ($transaction->status == COMMERCE_PAYMENT_STATUS_FAILURE) {
        $status = $data['AuthorizationStatus'];
        switch ($status['ReasonCode']) {
          case 'AmazonRejected':
            $message = t('Your order could not be completed, your payment was rejected.');
            drupal_set_message($message, 'error');
            $order_wrapper->{AmazonLPA::REFERENCE_ID_FIELD} = '';
            commerce_order_status_update($order, 'cart', FALSE, TRUE, t('Order changed to cart because it was rejected by Amazon'));
            drupal_goto('cart');
            break;

          case 'TransactionTimedOut':
            $message = t('The payment authorization timed out. Please try again or contact the store.');
            drupal_set_message($message, 'error');
            $api->cancel($order_wrapper);
            $order_wrapper->{AmazonLPA::REFERENCE_ID_FIELD} = '';
            commerce_order_status_update($order, 'cart', FALSE, TRUE, t('Order changed to cart because the Amazon Pay transaction timed out.'));
            drupal_goto('cart');
            break;

          case 'ProcessingFailure':
            $message = t('Amazon could not process the transaction due to an internal processing error. Please try again');
            break;

          case 'InvalidPaymentMethod':
          default:
            $message = t('There were problems with the payment method. Please select another payment method from the Amazon Payments wallet.');
            break;
        }
        drupal_set_message($message, 'error');
        commerce_order_status_update($order, 'checkout_' . $commerce_payment_pane['page'], FALSE, NULL, $message);
        drupal_goto(commerce_checkout_order_uri($order));
      }
    }
  }
  catch (Exception $e) {
    drupal_set_message(t('There were problems with the payment method. Please select another payment method from the Amazon Payments wallet.'), 'error');
    commerce_order_status_update($order, 'checkout_' . $commerce_payment_pane['page'], FALSE, NULL, $e->getMessage());
    drupal_goto(commerce_checkout_order_uri($order));
  }

  commerce_payment_redirect_pane_next_page($order, t('Order paid with Amazon Payments'));
  drupal_goto(commerce_checkout_order_uri($order));
}
