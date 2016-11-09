<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Base.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\payment\Entity\PaymentInterface;
use Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic;
use PayPal\Api\VerifyWebhookSignature;

/**
 * Abstract class for redirects and webhooks.
 */
abstract class Base extends ControllerBase {

  /**
   * @param PaymentInterface $payment
   * @return bool
   */
  protected function verify(PaymentInterface $payment) {
    /** @var PayPalBasic $payment_method */
    $payment_method = $payment->getPaymentMethod();

    $resource = new VerifyWebhookSignature();
    # TODO: Set properties in $resource from $body
    try {
      $response = $resource->post($payment_method->getApiContext());
      if ($response->getVerificationStatus() != 'SUCCESS') {
        return TRUE;
      }
    } catch (\Exception $ex) {
      // TODO: Error handling
    }
    return FALSE;
  }

  public function access(PaymentInterface $payment) {
    return AccessResult::allowedIf($this->verify($payment));
  }

}
