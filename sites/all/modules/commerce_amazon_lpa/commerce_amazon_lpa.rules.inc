<?php

/**
 * @file
 * Rules integration for Commerce Amazon LPA.
 *
 * @addtogroup rules
 * @ {
 */

/**
 * Implements hook_rules_event_info().
 */
function commerce_amazon_lpa_rules_event_info() {
  $variables = array_merge(
    entity_rules_events_variables('commerce_order', t('Order', array(), array('context' => 'a drupal commerce order')), TRUE),
    entity_rules_events_variables('commerce_payment_transaction', t('Last completed transaction'), TRUE),
    array('data' => array('type' => 'struct', 'label' => t('Data returned by Amazon')))
  );

  $events['commerce_amazon_lpa_nonsync_auth_soft_decline'] = array(
    'label' => t('When an Amazon Payments transaction has a soft decline'),
    'group' => t('Commerce Amazon LPA'),
    'variables' => $variables,
    'access callback' => 'commerce_order_rules_access',
  );

  $events['commerce_amazon_lpa_nonsync_auth_hard_decline'] = array(
    'label' => t('When an Amazon Payments transaction has a hard decline'),
    'group' => t('Commerce Amazon LPA'),
    'variables' => $variables,
    'access callback' => 'commerce_order_rules_access',
  );
  return $events;
}

/**
 * Implements hook_rules_condition_info().
 */
function commerce_amazon_lpa_rules_condition_info() {
  return array(
    'commerce_amazon_lpa_is_amazon_order' => array(
      'label' => t('Is an Amazon Payments order'),
      'parameter' => array(
        'commerce_order' => array(
          'type' => 'commerce_order',
          'label' => t('Order'),
          'description' => t('The order to be checked.'),
        ),
      ),
      'group' => t('Commerce Amazon Login & Pay'),
      'callbacks' => array(
        'execute' => 'commerce_amazon_lpa_is_amazon_order',
      ),
    ),
  );
}

/**
 * Condition callback for commerce_amazon_lpa_is_amazon_order
 *
 * @param $order
 *   The order to check.
 *
 * @return bool
 *   Returns TRUE if the order is for Amazon Payments.
 */
function commerce_amazon_lpa_is_amazon_order($order) {
  return !empty($order->data['commerce_amazon_lpa']);
}

/**
 * Implements hook_default_rules_configuration_alter().
 */
function commerce_amazon_lpa_default_rules_configuration_alter(&$configs) {
  if (isset($configs['commerce_checkout_order_status_update'])) {
    /** @var RulesReactionRule $status_update_role */
    $status_update_role =& $configs['commerce_checkout_order_status_update'];

    $is_not_awating_auth_status = rules_condition('data_is', array(
      'data:select' => 'commerce-order:status',
      'op' => '==',
      'value' => 'awaiting_auth',
    ))->negate();

    $is_not_auth_order_status = rules_condition('data_is', array(
      'data:select' => 'commerce-order:status',
      'op' => '==',
      'value' => variable_get('commerce_amazon_lpa_auth_order_status', 'pending'),
    ))->negate();

    $status_update_role->condition($is_not_awating_auth_status);
    $status_update_role->condition(rules_or()->condition($is_not_auth_order_status));
  }

  if (isset($configs['commerce_payment_commerce_amazon_login_and_pay'])) {
    /** @var RulesReactionRule $rule */
    $rule =& $configs['commerce_payment_commerce_amazon_login_and_pay'];
    $rule->condition(rules_condition('commerce_amazon_lpa_is_amazon_order'));
  }
}

/**
 * @}
 */
