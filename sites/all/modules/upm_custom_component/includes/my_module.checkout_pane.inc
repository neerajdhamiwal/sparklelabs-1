<?php
/**
 * Implements base_settings_form()
 */
function my_module_pane_settings_form($checkout_pane) {
    $form['my_module_pane_field'] = array(
        '#type' => 'textfield',
        '#title' => t('My Module Pane Field'),
        '#default_value' => variable_get('my_module_pane_field', ''),
    );
    return $form;
}
/**
 * Implements base_checkout_form()
 */

function my_module_pane_checkout_form($form, $form_state, $checkout_pane, $order) {
    $options = array();
    $keys = array();
    $terms = array();
    foreach($order->commerce_line_items['und'] as $items ){
        $line_item = $items['line_item_id'];
        $lineItemObj = commerce_line_item_load($line_item); // alternate line of code to resolve ajax error
        $line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);
        //if ($line_item_wrapper->type->value() == 'product') {
        if ($lineItemObj->type == 'product') { // alternate line of code to resolve ajax error
            //echo "<pre>".$line_item_wrapper->commerce_product->raw();print_r($lineItemObj);echo "<pre>";
            $product = $lineItemObj->data['context']['product_ids'][0];
            //$product = $line_item_wrapper->commerce_product->raw();
            $query = new EntityFieldQuery;
            $query->entityCondition('entity_type', 'node', '=')
                ->propertyCondition('type', 'product')
                ->fieldCondition('field_product_reference', 'product_id', $product, '=')
                ->range(0, 1);

            if ($result = $query->execute()) {
              foreach($result['node'] as $node){
                $node = node_load($node->nid);
                $terms[]=taxonomy_get_term_by_name($node->title, 'newsletter');
              }
            }
        }
    }
    foreach($terms as $term ){
        $keys[] = key($term);
        $options[key($term)] = $term[key($term)]->name;
    }
    $email = (isset($order->field_email['und'][0]['value']))? $order->field_email['und'][0]['value'] : '';
    $checkout_form['my_module_pane_news_email'] = array(
        '#type' => 'textfield',
        '#title' => t('Email Address'),
        '#required' => TRUE,
        '#default_value' => $email,
        '#attributes' => array(
            'placeholder' => t('Email'),
        ),
    );
    $checkout_form['my_module_pane_newsletters_list'] = array(
        '#type' => 'checkboxes',
        '#title'=> t('Get updates for these kits:'),
        '#options' => $options ,
        '#default_value' => $keys
    );
    $checkout_form['my_module_pane_news_check'] = array(
        '#type' => 'checkbox',
        '#title'=> t('Sign up for our newsletter. 2-3 times a year.'),
        '#default_value' =>TRUE
    );

    return $checkout_form;
}

/**
 * Implements base_checkout_form_submit()
 */

function my_module_pane_checkout_form_submit($form, &$form_state, $checkout_pane, $order) {
    $order->field_email['und'][0]['value']=$form_state['values']['my_module']['my_module_pane_news_email'];
    commerce_order_save($order);
    $email = $form_state['values']['my_module']['my_module_pane_news_email'];
    $type_list = $form_state['values']['my_module']['my_module_pane_newsletters_list'];
    foreach($type_list as $key => $type){
        if(empty(simplenews_category_load($type))){
            $simplenews_taxonomy_id = $type;
            $category = new stdClass();
            $category->tid = $simplenews_taxonomy_id;
            $category->format = 'html';
            $category->priority = 0;
            $category->receipt = 0;
            $category->from_name = variable_get('site_name', 'Asist Local');
            $category->email_subject = "[" . "node:title" . "]";
            $category->from_address = variable_get('site_mail', '');
            $category->hyperlinks = 1;
            $category->new_account = 'off';
            $category->opt_inout = 'double';
            $category->block = 1;
            $category->name = '';
            $category->description = '';
            $category->$simplenews_taxonomy_id = $simplenews_taxonomy_id;
            simplenews_category_save($category);
            simplenews_subscribe_user($email, $type, FALSE);
        }
        else{
            simplenews_subscribe_user($email, $type, FALSE);
        }
    }
    if($form_state['values']['my_module']['my_module_pane_news_check'] == 1){
       simplenews_subscribe_user($email, 1, FALSE);
    }

}
