<?php
/**
 * @file
 * Contains all code for the gift wrap pane. Called from
 *  commerce_giftwrap_commerce_checkout_pane_info()
 */

/**
 * Implements base_settings_form().
 */
function upm_commerce_giftwrap_pane_settings_form($checkout_pane) {
  // Load the default currency for this instance.
  $default_currency = commerce_default_currency();
  $currency = commerce_currency_load($default_currency);
  // Round the default value.
  $default_amount = commerce_currency_amount_to_decimal(
      variable_get('commerce_giftwrap_price', '300'),
      $currency['code']
  );
  $form = array();
  $form['commerce_giftwrap_price'] = array(
    '#type' => 'textfield',
    '#title' => t('Gift Wrapping Price'),
    '#size' => 10,
    '#default_value' => $default_amount,
    '#field_suffix' => $currency['code'],
  );
  return $form;
}

/**
 * Custom submit function to handle the commerce_giftwrap_price.
 */
function upm_commerce_giftwrap_pane_settings_form_submit($form, &$form_state) {
  // Load the default currency for this instance.
  $default_currency = commerce_default_currency();
  $currency = commerce_currency_load($default_currency);
  // Get the decimal price.
  $decimal_price = $form_state['values']['commerce_giftwrap_price'];
  // Convert decimal price to amount for storage.
  $amount_price = commerce_currency_decimal_to_amount($decimal_price, $currency['code']);
  variable_set('commerce_giftwrap_price', $amount_price);
}

/**
 * Implements base_checkout_form().
 */
function upm_commerce_giftwrap_pane_checkout_form($form, &$form_state, $checkout_pane, $order) {
    $checkout_form['my_gift_pane_msg'] = array(
        '#markup' => variable_get('my_gift_pane_field', ''),
    );
    $check = (isset($order->field_gift_price['und'][0]['value']))? TRUE : FALSE;
    $checkout_form['my_gift_pane_gift_check'] = array(
        '#type' => 'checkbox',
        '#title'=> t('Yes, this is a gift'),
        '#default_value' => $check
    );

    $checkout_form['my_gift_block'] = array(
        '#type' => 'fieldset',
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
        '#tree' => TRUE,
        '#states' => array(
            'visible' => array(
                ':input[name="commerce_giftwrap[my_gift_pane_gift_check]"]' => array('checked' => TRUE),
            ),
        ),
    );
    $option = (isset($order->field_gift_price['und'][0]['value']) && $order->field_gift_price['und'][0]['value'] == 'Wrap')? 'price' : 'free';
    $number = variable_get('commerce_giftwrap_price', '300') / 100;
    $rate = round($number ,2);
    $checkout_form['my_gift_block']['my_gift_pane_gift_option'] = array(
        '#type' => 'radios',
        '#title'=> t('Choose from these options:'),
        '#options' => array('free' => t('Gift Messaging (free)'),'price' => 'Gift Wrapping ($'.$rate.' per item)<br><small>with optional gift message</small>'),
        '#default_value' => $option,
    );
    $gift_msg = (isset($order->field_gift_message['und'][0]['value']))? $order->field_gift_message['und'][0]['value'] : '';
    $checkout_form['my_gift_block']['my_gift_pane_gift_msg'] = array(
        '#type' => 'textarea',
        '#title'=> t(''),
        '#default_value' => $gift_msg,
        '#attributes' => array(
            'placeholder' => t('Type your message here')
        )
    );
    return $checkout_form;
}

/**
 * Implements base_checkout_form_submit().
 */
function upm_commerce_giftwrap_pane_checkout_form_submit($form, &$form_state, $checkout_pane, $order) {

  $giftwrap_decision = $form_state['values']['commerce_giftwrap']['my_gift_pane_gift_check'];
  upm_commerce_giftwrap_delete_giftwrap_line_items($order);
  if ($giftwrap_decision) {
      $is_price = $form_state['values']['commerce_giftwrap']['my_gift_block']['my_gift_pane_gift_option'];
      $order->field_gift_price['und'][0]['value']=($is_price == 'price')?'Wrap':'Free';
      $order->field_gift_message['und'][0]['value']=$form_state['values']['commerce_giftwrap']['my_gift_block']['my_gift_pane_gift_msg'];
      if($is_price == 'price' ) {
          // Set the currency code.
          $default_currency_code=commerce_default_currency();
          if ($balance=commerce_payment_order_balance($order)) {
              $default_currency_code=$balance['currency_code'];
          }
          $quantity_count=0;
          $order_wrapper=entity_metadata_wrapper('commerce_order', $order);
          foreach ($order_wrapper->commerce_line_items as $delta=>$line_item_wrapper) {
              if ($line_item_wrapper->type->value() == 'product') {
                  $quantity_count+=$line_item_wrapper->quantity->value();
              }
          }
          // Create the new line item.
          $line_item=commerce_line_item_new('giftwrap', $order->order_id);
          // Wrap the line item and order to simplify manipulating their field data.
          $order_wrapper=entity_metadata_wrapper('commerce_order', $order);
          $line_item_wrapper=entity_metadata_wrapper('commerce_line_item', $line_item);
          // Populate the $line_item_wrapper...
          $line_item_wrapper->line_item_label=t('Gift Wrapping');
          $line_item_wrapper->quantity=1;
          $line_item_wrapper->commerce_unit_price->amount=$quantity_count * variable_get('commerce_giftwrap_price', '300');
          $line_item_wrapper->commerce_unit_price->currency_code=$default_currency_code;
          // Set the price component of the unit price.
          $line_item_wrapper->commerce_unit_price->data=commerce_price_component_add(
              $line_item_wrapper->commerce_unit_price->value(),
              'giftwrap',
              $line_item_wrapper->commerce_unit_price->value(),
              TRUE,
              FALSE
          );
          // Invoke the 'commerce_giftwrap_calculate' event.
          rules_invoke_all('commerce_giftwrap_calculate', $line_item);
          // Save the incoming line item now so we get its ID.
          commerce_line_item_save($line_item);
          // Add it to the order's line item reference value.
          $order_wrapper->commerce_line_items[]=$line_item;
          // Save the order.
      }
    commerce_order_save($order);
  }
  elseif (!$giftwrap_decision) {
    // If the user selects 'No' check if giftwrapping has been added, if so
    // delete it.
      unset($order->field_gift_price['und']);
      unset($order->field_gift_message['und']);
  }
}

/**
 * Implements base_review().
 */
//function commerce_giftwrap_pane_review($form, &$form_state, $checkout_pane, $order) {
//  $message = commerce_giftwrap_get_message($order);
//  if (commerce_giftwrap_line_item_exists($order)) {
//    $output = array(
//      'giftwrap_decision' => array(
//        '#type' => 'markup',
//        '#markup' => '<h3>' . t('Would you like your order gift wrapped?') . ' <strong>' . t('Yes') . '</strong></h3>',
//      ),
//      'title' => array(
//        '#type' => 'markup',
//        '#markup' => '<h3>' . t('Message:') . '</h3>',
//      ),
//      'giftwrap_message' => array(
//        '#type' => 'markup',
//        '#markup' => '<p>' . filter_xss($message) . '</p>',
//      ),
//    );
//  }
//  else {
//    $output['giftwrap_decision'] = array(
//      '#type' => 'markup',
//      '#markup' => '<h3>' . t('Would you like your order gift wrapped?') . '<strong>' . t('No') . '</strong></h3>',
//    );
//  }
//  return drupal_render($output);
//}
