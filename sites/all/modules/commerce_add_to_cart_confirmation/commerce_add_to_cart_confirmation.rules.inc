<?php

/**
 * @file
 * Rules integration for the Commerce add to cart confirmation module.
 */

/**
 * Implements hook_rules_action_info().
 */
function commerce_add_to_cart_confirmation_rules_action_info() {
  return array(
    'commerce_add_to_cart_confirmation_message' => array(
      'label' => t('Add to Cart message'),
      'parameter' => array(
        'commerce_line_item' => array(
          'type' => 'commerce_line_item',
          'label' => t('Line item'),
        ),
      ),
      'group' => t('Commerce (contrib)'),
      'callbacks' => array(
        'execute' => 'commerce_add_to_cart_confirmation_rules_add_to_cart_message',
      ),
    ),
  );
}

/**
 * Rules action: displays a custom Add to Cart message.
 */
function commerce_add_to_cart_confirmation_rules_add_to_cart_message($line_item, $continue) {

  $message = '<div class="added-product-title clearfix">' . t('Item successfully added to your cart') . '</div>';
  drupal_set_message('<div class="commerce-cart-confirmation"><div class="message-inner">' . $message . '</div></div>', 'commerce_product_added');

}

//  $message = '<div class="added-product-title clearfix">' . t('Item successfully added to your cart') . '</div>';
//  $message .= views_embed_view('commerce_cart_block', 'default', $line_item->order_id);
//  drupal_set_message('<div class="commerce-add-to-cart-confirmation"><a class="close" data-dismiss="alert" href="#">×</a><div class="message-inner">' . $message . '</div></div>', 'commerce_add_to_cart_confirmation_overlay');
