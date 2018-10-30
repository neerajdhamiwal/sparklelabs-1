PAYMENT CREDIT CARD
-------------------

This simple module does nothing by itself : this is
a module helper for developer who need to integrate an embeddable credit card
form to a payment method form.

This module is fully copied from commerce source
(payment module, http://drupal.org/project/commerce).
It will make some check required on basic input credit card form :

- card type
- card number (using Luhn algorithm)
- security code
- expiration date
- start date if provided

For e.g to implements a basic credit card form  :

<code>
module_load_include('inc', 'payment_credit_card', 'payment_credit_card.credit_card');

//Provide optional fields to include 
//or fields with some settings
$fields = array(
  'type' => array('visa', 'mastercard'),
  'code' => '',
);

//Get credit card form
return payment_credit_card_credit_card_form($fields);

</code>

In the example above, no validate handlers have been executed.
Indeed, this is in charge of the caller to execute it.

<code>

//Get credit card values
$credit_card = $form_state['values'][<your credit card form embedded tree>];
//Get an array of credit card parents
$parents = <parents array of credit card form, usually store in #parents>;

module_load_include('inc', 'payment_credit_card', 'payment_credit_card.credit_card');
$settings = array(
  'form_parents' => $parents,
);

//Call main validate handler and execute some checks 
//on credit card form values at a first control level
if (!payment_credit_card_credit_card_validate($credit_card, $settings)) {
  return FALSE;
}

</code>

NOTE : this will make only some basic checks on raw input.
