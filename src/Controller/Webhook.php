<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Webhook.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\payment\Entity\PaymentInterface;

/**
 * Handles the "webhook" route.
 */
class Webhook extends ControllerBase {

  /**
   * PayPal calls this after the payment status has been changed. PayPal only
   * gives us an id leaving us with the responsibility to get the payment status.
   *
   * @param \Drupal\payment\Entity\PaymentInterface $payment
   *
   * @return
   */
  public function execute(PaymentInterface $payment) {
    return new Response();
  }

}
