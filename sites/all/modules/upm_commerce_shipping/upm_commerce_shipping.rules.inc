<?php
/**
 * @file
 * Contains rules events, conditions and actions for the Shipping Module.
 */

/*
 * Implements hook_rules_event_info().
 */
 
function upm_commerce_shipping_rules_event_info() {
  // The line item rule file is needed for the access callback.
  module_load_include('inc', 'commerce_line_item', 'commerce_line_item.rules');
  $items = array(
    'commerce_shipping_rates_calculate' => array(
      'label' => t('Calculate the shipping price for a line item'),
      'group' => t('Commerce Shipping rates'),
      'variables' => entity_rules_events_variables('commerce_line_item', t('Line Item', array(), array('context' => 'a drupal commerce line item'))),
      'access callback' => 'commerce_line_item_rules_access',
    ),
  );
  return $items;
}