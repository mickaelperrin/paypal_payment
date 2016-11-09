<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Webhook.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\payment\Entity\PaymentInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles the "webhook" route.
 */
class Webhook extends Base {

  /**
   * PayPal calls this after the payment status has been changed. PayPal only
   * gives us an id leaving us with the responsibility to get the payment status.
   *
   * @param PaymentInterface $payment
   * @return Response
   */
  public function execute(PaymentInterface $payment) {
    $request = \Drupal::request();
    $body = $request->getContent();

    # TODO: Implement the webhook, access has already been verified.

    return new Response();
  }

}
