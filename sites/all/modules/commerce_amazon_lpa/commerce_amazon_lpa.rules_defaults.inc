<?php

/**
 * @file
 * Default rule configurations for Commerce Amazon LPA.
 */

/**
 * Implements hook_default_rules_configuration().
 */
function commerce_amazon_lpa_default_rules_configuration() {
  $rules = array();

  $rules['commerce_amazon_lpa_soft_decline_email'] = entity_import('rules_config', '{ "commerce_amazon_lpa_soft_decline_email" : {
    "LABEL" : "Send customer email on soft Amazon payments decline",
    "PLUGIN" : "reaction rule",
    "OWNER" : "rules",
    "TAGS" : [ "Commerce Amazon LPA" ],
    "REQUIRES" : [ "rules", "commerce_amazon_lpa" ],
    "ON" : { "commerce_amazon_lpa_nonsync_auth_soft_decline" : [] },
    "DO" : [
      { "mail" : {
          "to" : [ "commerce-order:mail" ],
          "subject" : "Order [commerce-order:order-number] at [site:name]: Payment declined",
          "message" : "There were problems with the payment method for your order. Please update your payment method using the Amazon Payments website.\n\nPlease contact us if you have any questions about your order.",
          "language" : [ "" ]
        }
      }
    ]
  }
}');

  $rules['commerce_amazon_lpa_hard_decline_email'] = entity_import('rules_config', '{ "commerce_amazon_lpa_hard_decline_email" : {
    "LABEL" : "Send customer email on hard Amazon payments decline",
    "PLUGIN" : "reaction rule",
    "OWNER" : "rules",
    "TAGS" : [ "Commerce Amazon LPA" ],
    "REQUIRES" : [ "rules", "commerce_order", "commerce_amazon_lpa" ],
    "ON" : { "commerce_amazon_lpa_nonsync_auth_hard_decline" : [] },
    "DO" : [
      { "mail" : {
          "to" : [ "commerce-order:mail" ],
          "subject" : "Order [commerce-order:order-number] at [site:name]: Payment declined",
          "message" : "There were problems with the payment method for your order. Your payment was declined and the order has been canceled.\n\nPlease contact us if you have any questions about your order.",
          "language" : [ "" ]
        }
      },
      { "commerce_order_update_status" : { "commerce_order" : [ "commerce_order" ], "order_status" : "canceled" } }
    ]
  }
}');


  return $rules;
}
