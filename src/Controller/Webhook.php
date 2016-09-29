<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Webhook.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\payment\Entity\PaymentInterface;
use Drupal\paypal_payment\Entity\PayPalProfileInterface;
use Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic;
use PayPal\Api\WebhookEvent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles the "webhook" route.
 */
class Webhook extends ControllerBase {

  public function access(PaymentInterface $payment) {
    // TODO: check the request
    return AccessResult::allowedIf(TRUE);
  }

  /**
   * PayPal calls this after the payment status has been changed. PayPal only
   * gives us an id leaving us with the responsibility to get the payment status.
   *
   * @param PaymentInterface $payment
   * @return Response
   */
  public function execute(PaymentInterface $payment) {
    $request = \Drupal::request();
    #$body = $request->getContent();
    $body = file_get_contents('/tmp/141.json');

    /** @var PayPalBasic $payment_method */
    $payment_method = $payment->getPaymentMethod();
    /** @var PayPalProfileInterface $profile */
    $profile = $payment_method->getProfile();

    try {
      /** @var WebhookEvent $event */
      $event = WebhookEvent::validateAndGetReceivedEvent($body, $profile->getApiContext());
      // TODO: Interpret the event and do the right things.
    } catch (\Exception $ex) {
      // TODO: Error handling
    }

    return new Response();
  }

}
