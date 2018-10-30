<?php
/**
 * @file
 * Contains all code for the Shipping pane. Called from
 *  commerce_shipping_commerce_checkout_pane_info()
 */
/**
 * Implements base_checkout_form().
 */
function upm_commerce_shipping_pane_checkout_form($form, &$form_state, $checkout_pane, $order) {
    $query = db_select('shipping_options', 's')
        ->condition('type', 'local')
        ->fields('s' , array('id','label','rate'));
    $results =  $query->execute()->fetchAll();
    $options = array();
    foreach ($results as $result) {
        $human_readable = $result->label;
        $rates = $result->rate;
        $options[$result->id] = $human_readable.": $".$rates;

    }
    $query = db_select('shipping_options', 's')
        ->condition('type', 'inter')
        ->fields('s' , array('id','label','rate'));
    $inter_results =  $query->execute()->fetchAll();
    $inter_options = array();
    foreach ($inter_results as $result) {
        $human_readable = $result->label;
        $rates = $result->rate;
        $inter_options[$result->id] = $human_readable.": $".$rates;

    }
    
    $local_display = 'display:none';
    $inter_display = 'display:none';

    $id = upm_commerce_shipping_line_item_exists($order);
        
    if($id == 4 || $id == 5){
        $inter_display = 'display:block';
    }else{
        $local_display = 'display:block';
    }

    $slected = db_select('shipping_options', 's')
        ->fields('s' , array('type'))
        ->condition('id', $id)
        ->execute()
        ->fetchAssoc();
    if($slected['type'] == 'inter'){
        $inter_id = $id;
        $slected = db_select('shipping_options', 's')
            ->fields('s' , array('id'))
            ->condition('type', 'local')
            ->execute()
            ->fetchAssoc();
        $local_id = $slected['id'];
    }else{
        $local_id = $id;
        $slected = db_select('shipping_options', 's')
            ->fields('s' , array('id'))
            ->condition('type', 'inter')
            ->execute()
            ->fetchAssoc();
        $inter_id = $slected['id'];
    }
    $checkout_form['commerce_shipping_option'] = array(
        '#type' => 'radios',
        '#title'=> t(''),
        '#default_value' => $local_id,
        '#options' => $options,
        '#prefix' => '<div id="shipping-options-local" style="'.$local_display.'">',
        '#suffix' => '</div>',
    );
    $checkout_form['commerce_inter_shipping_option'] = array(
        '#type' => 'radios',
        '#title'=> t(''),
        '#default_value' => $inter_id,
        '#options' => $inter_options,
        '#prefix' => '<div id="shipping-options-inter" style="'.$inter_display.'">',
        '#suffix' => '</div>',
    );
    return $checkout_form;
}

/**
 * Implements base_checkout_form_submit().
 */
function upm_commerce_shipping_pane_checkout_form_submit($form, &$form_state, $checkout_pane, $order) {
    $profile_id = $order->commerce_customer_shipping['und'][0]['profile_id'];
    $profile = commerce_customer_profile_load($profile_id);
    $country = $profile->commerce_customer_address['und'][0]['country'];
    $shipping_id = 1;
    //upm_commerce_delete_tax_line_items($order);
    if($country == 'US'){
        $shipping_id = $form_state['values']['commerce_shipping_rates']['commerce_shipping_option'];
        $order->field_shipping_type['und'][0]['value'] = 'local';
        $order->field_shipping_id['und'][0]['value'] = $shipping_id;
        // if($profile->commerce_customer_address['und'][0]['administrative_area'] == 'NJ'){
        //     add_tax($order);
        // }
    }else{
         $shipping_id = $form_state['values']['commerce_shipping_rates']['commerce_inter_shipping_option'];
         $order->field_shipping_type['und'][0]['value'] = 'inter';
         $order->field_shipping_id['und'][0]['value'] = $shipping_id;
     }
  $slected = db_select('shipping_options', 's')
        ->fields('s' , array('id','label','rate','type'))
        ->condition('id', $shipping_id)
        ->execute()
        ->fetchAssoc();
  $shipping_label = $slected['label'];
  $shipping_price = $slected['rate'];
  $machine_readable = strtolower($shipping_label);
  $machine_readable = preg_replace('@[^a-z0-9_]+@','_',$machine_readable);
  upm_commerce_delete_shipping_line_items($order);

  // Set the currency code.
  $default_currency_code=commerce_default_currency();
  if ($balance=commerce_payment_order_balance($order)) {
     $default_currency_code=$balance['currency_code'];
  }

  // Create the new line item.
  $line_item=commerce_line_item_new('shipping_rates', $order->order_id);
  // Wrap the line item and order to simplify manipulating their field data.
          $order_wrapper=entity_metadata_wrapper('commerce_order', $order);
          $line_item_wrapper=entity_metadata_wrapper('commerce_line_item', $line_item);
          // Populate the $line_item_wrapper...
          $line_item_wrapper->line_item_label= $machine_readable."_".$shipping_id;
          $line_item_wrapper->quantity=1;
          $line_item_wrapper->commerce_unit_price->amount =  $shipping_price * 100;
          $line_item_wrapper->commerce_unit_price->currency_code = $default_currency_code;
          // Set the price component of the unit price.
          $line_item_wrapper->commerce_unit_price->data=commerce_price_component_add(
              $line_item_wrapper->commerce_unit_price->value(),
              $machine_readable,
              $line_item_wrapper->commerce_unit_price->value(),
              TRUE,
              FALSE
          );
          // Invoke the 'commerce_giftwrap_calculate' event.
          rules_invoke_all('commerce_shipping_rates_calculate', $line_item);
          // Save the incoming line item now so we get its ID.
          commerce_line_item_save($line_item);
          // Add it to the order's line item reference value.
          $order_wrapper->commerce_line_items[]=$line_item;
          // Save the order.
    commerce_order_save($order);

}
function add_tax($order){

    $rates = db_select('commerce_tax_rate', 'r')
        ->fields('r')
        ->execute()
        ->fetchAssoc();
    $tax_rate = $rates['rate'];

    foreach ($order->commerce_order_total['und'][0]['data']['components'] as $components) {
        if ($components['name'] == 'base_price') {
            $base_price=$components['price']['amount'];
        } else if ($components['name'] == 'discount') {
            $discount=$components['price']['amount'];

        }
    }
    $base_price = $base_price + $discount;
    $total = $base_price / 100;

    $tax_total = $tax_rate * $total ;
    // Set the currency code.
    $default_currency_code = commerce_default_currency();
    if ($balance = commerce_payment_order_balance($order)) {
        $default_currency_code=$balance['currency_code'];
    }
    // Create the new line item.
    $line_item = commerce_line_item_new('tax_rates', $order->order_id);
    // Wrap the line item and order to simplify manipulating their field data.
    $order_wrapper = entity_metadata_wrapper('commerce_order', $order);
    $line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);
    // Populate the $line_item_wrapper...
    $line_item_wrapper->line_item_label = 'Sales Tax';
    $line_item_wrapper->quantity = 1;
    $line_item_wrapper->commerce_unit_price->amount =  $tax_total * 100;
    $line_item_wrapper->commerce_unit_price->currency_code = $default_currency_code;
    // Set the price component of the unit price.
    $line_item_wrapper->commerce_unit_price->data = commerce_price_component_add(
        $line_item_wrapper->commerce_unit_price->value(),
        'tax_rates',
        $line_item_wrapper->commerce_unit_price->value(),
        TRUE,
        FALSE
    );
    // Invoke the 'commerce_tax_rates_calculate' event.
    rules_invoke_all('commerce_tax_rates_calculate', $line_item);
    // Save the incoming line item now so we get its ID.
    commerce_line_item_save($line_item);
    // Add it to the order's line item reference value.
    $order_wrapper->commerce_line_items[] = $line_item;
    // Save the order.
    commerce_order_save($order);
}

