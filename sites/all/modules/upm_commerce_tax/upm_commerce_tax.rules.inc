<?php
/**
 * @file
 * Contains rules events, conditions and actions for the Tax Module.
 */

/*
 * Implements hook_rules_event_info().
 */
 
function upm_commerce_tax_rules_event_info() {
  module_load_include('inc', 'commerce_line_item', 'commerce_line_item.rules');
  $items = array(
    'commerce_tax_rates_calculate' => array(
      'label' => t('Calculate the tax rate for a line item'),
      'group' => t('Commerce Tax rates'),
      'variables' => entity_rules_events_variables('commerce_line_item', t('Line Item', array(), array('context' => 'a drupal commerce line item'))),
      'access callback' => 'commerce_line_item_rules_access',
    ),
  );
  return $items;
}