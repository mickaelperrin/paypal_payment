<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Redirect.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\payment\Entity\PaymentInterface;

/**
 * Handles the "redirect" route.
 */
class Redirect extends ControllerBase {

  /**
   * PayPal is redirecting the visitor here after the payment process. At this
   * point we don't know the status of the payment yet so we can only load
   * the payment and give control back to the payment context.
   *
   * @param \Drupal\payment\Entity\PaymentInterface $payment
   *
   * @return
   */
  public function execute(PaymentInterface $payment) {
    $response = $payment->getPaymentType()->getResumeContextResponse();

    return $response->getResponse();
  }

}
