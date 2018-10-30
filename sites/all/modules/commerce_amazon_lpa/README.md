Login and Pay with Amazon
-------------------------

This module integrates Login and Pay with Amazon with Drupal and [Drupal Commerce][drupalcommerce].

Amazon Payments provides Amazon buyers a secure, trusted, and convenient way to log in and pay for their purchases on your site. Buyers use Login and Pay with Amazon to share their profile information (name and email address) and access the shipping and payment information stored in their Amazon account to complete their purchase. Learn more at

* [Amazon Payments][amazonpayments]
* [Amazon Payments UK][amazonpayments_uk]
* [Amazon Payments DE][amazonpayments_de]

[amazonpayments]: https://payments.amazon.com
[amazonpayments_uk]: https://payments.amazon.co.uk
[amazonpayments_de]: https://payments.amazon.de
[drupalcommerce]: https://www.drupal.org/project/commerce

## Requirements

You must have [Drupal Commerce][drupalcommerce] and the Cart, Customer, and Payment submodules enabled.

The shop owner must have an Amazon merchant account. Sign up now:
* US : https://payments.amazon.com/business/pre-registration?ld=SPEXUKCBADrupal
* UK : https://payments.amazon.co.uk/business/pre-registration?ld=SPEXUKCBADrupal
* DE : https://payments.amazon.de/business/pre-registration?ld=SPEXDECBADrupal

This module utilizes the [Login and Pay with Amazon PHP SDK][php-sdk] to communicate with Amazon. You must have the [Libraries][libraries] module installed in order to load the SDK properly.

[php-sdk]: https://github.com/amzn/amazon-pay-sdk-php/releases
[libraries]: https://www.drupal.org/project/libraries

## Features

The module's integration provides the following features:

* When using the *Login and Pay* mode, users logging in with their Amazon.com accounts will have an account created in Drupal.
* Ability to provide the normal checkout experience or only provide Amazon based checkout.
* Multilingual support
* Support for United States, United Kingdom, and Germany regions.

## Installation

1. Install as you would normally install a contributed drupal module and its dependencies. See: https://drupal.org/documentation/install/modules-themes/modules-7 for further information.
1. Download the latest 2.x PHP SDK from [php-sdk] and place it in *sites/all/libraries*
1. Visit *admin/commerce/config/amazon-lpa* and enter your Merchant ID, MWS Access Key, MWS Secret key, and LWA Client ID.
1. Save the configuration, your API credentials will be validated.
1. Add the following URLs as *Allowed Return URLs* in order to support Login with Amazon.
* https://example.com/checkout/amazon
* https://example.com/cart
* https://example.com/user/login/amazon?amzn=LwA
6. Add https://example.com/commerce-amazon-lpa/ipn as your *Merchant URL* in the *Integration Settings* form. 

## Frequently Asked Questions

### Only allow users to login through Amazon

You have the ability to disable Drupal's user registration and support registration and login solely through Login with Amazon.

1. Visit *admin/commerce/config/amazon-lpa* 
1. Ensure the **Operation mode** is set to *Login and Pay mode*
1. Visit *admin/config/people/accounts*
1. Change **Who can register accounts?** to *Administrators only*

### Using just Pay with Amazon

You can use the Login and Pay with Amazon module to only support Pay with Amazon and the Amazon checkout.

1. Visit *admin/commerce/config/amazon-lpa* 
1. Ensure the **Operation mode** is set to *Pay only mode*

When entering the Amazon checkout, user's will be prompt to log in with their Amazon account before beginning. However, no Drupal account will be created.

### Adjusting button appearance

You have the ability to adjust the button settings for the Login with Amazon button and Pay with Amazon button. These are found on the configuration page at *admin/commerce/config/amazon-lpa* 

Buttons can be set to Small, Medium, Large, or Extra Large. The button's style can be Gold, Light Gray, Dark Gray. Buttons default to Medium and Gold.

Buttons are rendered using Drupal's theme system. See ````commerce_amazon_lpa_theme```` for the available theme hooks that can be invoked and processed.

## Maintainers

Current maintainer:
* Matt Glaman ([mglaman])

Development sponsored by **[Commerce Guys][commerceguys]**:

Commerce Guys are the creators of and experts in Drupal Commerce, the eCommerce solution that capitalizes on the virtues and power of Drupal, the premier open-source content management system. We focus our knowledge and expertise on providing online merchants with the powerful, responsive, innovative eCommerce solutions they need to thrive.

[mglaman]: https://www.drupal.org/u/mglaman
[commerceguys]: https://commerceguys.com/
