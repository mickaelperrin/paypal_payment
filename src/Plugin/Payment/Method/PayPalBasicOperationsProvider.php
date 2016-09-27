<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasicOperationsProvider.
 */

namespace Drupal\paypal_payment\Plugin\Payment\Method;

use Drupal\payment\Plugin\Payment\Method\BasicOperationsProvider;

/**
 * Provides paypal_payment_basic operations based on config entities.
 */
class PayPalBasicOperationsProvider extends BasicOperationsProvider {

  /**
   * {@inheritdoc}
   */
  protected function getPaymentMethodConfiguration($plugin_id) {
    $entity_id = substr($plugin_id, 21);

    return $this->paymentMethodConfigurationStorage->load($entity_id);
  }

}
