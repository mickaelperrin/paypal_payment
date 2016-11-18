<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Webhook.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\payment\Entity\PaymentInterface;
use Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic;
use PayPal\Api\VerifyWebhookSignature;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles the "webhook" route.
 */
class Webhook extends Base {

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
      $response = $resource->post($payment_method->getApiContext($payment_method::PAYPAL_CONTEXT_TYPE_WEBHOOK));
      if ($response->getVerificationStatus() != 'SUCCESS') {
        return TRUE;
      }
    } catch (\Exception $ex) {
      // TODO: Error handling
    }
    return FALSE;
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
    $body = $request->getContent();

    # TODO: Implement the webhook, access has already been verified.

    return new Response();
  }

}
