<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic.
 */

namespace Drupal\paypal_payment\Plugin\Payment\Method;

use Drupal\Core\Url;
use Drupal\payment\OperationResult;
use Drupal\payment\Plugin\Payment\Method\Basic;
use Drupal\payment\Response\Response;
use Drupal\paypal_payment\Entity\PayPalExpressProfile;
use Drupal\paypal_payment\Entity\PayPalStandardProfile;

/**
 * PayPal payment method.
 *
 * @PaymentMethod(
 *   deriver = "\Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasicDeriver",
 *   id = "paypal_payment_basic",
 *   operations_provider = "\Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasicOperationsProvider",
 * )
 */
class PayPalBasic extends Basic {

  /**
   * @inheritDoc
   */
  public function getPaymentExecutionResult() {
    $profile_id = $this->pluginDefinition['profile'];
    $profile = PayPalStandardProfile::load($profile_id);

    $amount = 0;
    foreach ($this->getPayment()->getLineItems() as $line_item) {
      $amount += $line_item->getTotalAmount();
    }

    $redirectUrl = new Url('paypal_payment.redirect',
      array('payment' => $this->getPayment()->id()), array('absolute' => TRUE));
    $webhookUrl = new Url('paypal_payment.webhook',
      array('payment' => $this->getPayment()->id()), array('absolute' => TRUE));
    $payment_data = array(
      'amount' => $amount,
      'description' => $this->getPayment()->id(),
      'redirectUrl' => $redirectUrl->toString(TRUE)->getGeneratedUrl(),
      'webhookUrl' => $webhookUrl->toString(TRUE)->getGeneratedUrl(),
    );

    $url = Url::fromUri('we need the full uri here created from $payment_data and the $profile');
    $response = new Response($url);
    return new OperationResult($response);
  }

}
