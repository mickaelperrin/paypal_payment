<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\MethodConfiguration\PayPalBasic.
 */

namespace Drupal\paypal_payment\Plugin\Payment\MethodConfiguration;

use Drupal\payment\Plugin\Payment\MethodConfiguration\Basic;

/**
 * Provides the configuration for the paypal_payment_basic payment method plugin.
 *
 * @PaymentMethodConfiguration(
 *   description = @Translation("PayPal payment method type."),
 *   id = "paypal_payment_basic",
 *   label = @Translation("PayPal")
 * )
 */
class PayPalBasic extends Basic {

}
