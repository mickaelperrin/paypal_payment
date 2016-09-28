<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasicDeriver.
 */

namespace Drupal\paypal_payment\Plugin\Payment\Method;

use Drupal\payment\Plugin\Payment\Method\BasicDeriver;

/**
 * Derives payment method plugin definitions based on configuration entities.
 */
class PayPalBasicDeriver extends BasicDeriver {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    /** @var \Drupal\payment\Entity\PaymentMethodConfigurationInterface[] $payment_methods */
    $payment_methods = $this->paymentMethodConfigurationStorage->loadMultiple();
    foreach ($payment_methods as $payment_method) {
      if ($payment_method->getPluginId() == 'paypal_payment_basic') {
        /** @var \Drupal\paypal_payment\Plugin\Payment\MethodConfiguration\PayPalBasic $configuration_plugin */
        $configuration_plugin = $this->paymentMethodConfigurationManager->createInstance($payment_method->getPluginId(), $payment_method->getPluginConfiguration());
        $this->derivatives[$payment_method->id()] = array(
          'id' => $base_plugin_definition['id'] . ':' . $payment_method->id(),
          'active' => $payment_method->status(),
          'label' => $configuration_plugin->getBrandLabel() ? $configuration_plugin->getBrandLabel() : $payment_method->label(),
          'profile' => $configuration_plugin->getProfile(),
          'message_text' => $configuration_plugin->getMessageText(),
          'message_text_format' => $configuration_plugin->getMessageTextFormat(),
          'execute_status_id' => $configuration_plugin->getExecuteStatusId(),
          'capture' => $configuration_plugin->getCapture(),
          'capture_status_id' => $configuration_plugin->getCaptureStatusId(),
          'refund' => $configuration_plugin->getRefund(),
          'refund_status_id' => $configuration_plugin->getRefundStatusId(),
        ) + $base_plugin_definition;
      }
    }

    return $this->derivatives;
  }

}
