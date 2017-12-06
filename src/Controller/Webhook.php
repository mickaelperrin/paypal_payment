<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Webhook.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic;
use PayPal\Api\VerifyWebhookSignature;
use PayPal\Api\WebhookEvent;
use Symfony\Component\HttpFoundation\Response;
use Drupal\payment\Entity\Payment as PaymentEntity;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Drupal\payment\Payment;

/**
 * Handles the "webhook" route.
 */
class Webhook extends ControllerBase {

  public function access($payment_method_id) {
    return AccessResult::allowedIf($this->verify($payment_method_id));
  }

  /**
   * @param string $payment_method_id
   * @return bool
   */
  private function verify(string $payment_method_id) {
    $request = \Drupal::request();
    try {
      /** @var PayPalBasic $payment_method */
      $payment_method = \Drupal\payment\Payment::methodManager()->createInstance('paypal_payment_express:' . $payment_method_id);
      if (!($payment_method instanceof PayPalBasic)) {
        throw new \Exception('Unsupported web hook');
      }

      $webhook = new WebhookEvent($request->getContent());

      $resource = new VerifyWebhookSignature();
      $resource->setAuthAlgo($request->headers->get('paypal-auth-algo'));
      $resource->setCertUrl($request->headers->get('paypal-cert-url'));
      $resource->setTransmissionId($request->headers->get('paypal-transmission-id'));
      $resource->setTransmissionSig($request->headers->get('paypal-transmission-sig'));
      $resource->setTransmissionTime($request->headers->get('paypal-transmission-time'));
      $resource->setWebhookEvent($webhook);
      $resource->setWebhookId($payment_method->getWebhookId());

      $response = $resource->post($payment_method->getApiContext($payment_method::PAYPAL_CONTEXT_TYPE_WEBHOOK));
      if ($response->getVerificationStatus() == 'SUCCESS') {
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
   * @param string $payment_method_id
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function execute(string $payment_method_id) {
    $request = \Drupal::request();
    $body = $request->getContent();

    // Validate body response
    if (!(
            ($bodyArray=json_decode($body, true))
            && array_key_exists('resource', $bodyArray)
            && array_key_exists('invoice_number', $bodyArray['resource']
            )
          )
    ) {
      throw new HttpException(500, 'Bad webhook request');
    }

    // Grab payment ID from the invoice number field
    $paymentId = $bodyArray['resource']['invoice_number'];

    // Get the entity ID and load it.
    if (!$this->payment = PaymentEntity::load($paymentId)) {
      throw new HttpException(404, 'Payment Not Found');
    }

    // Convert event type from Paypal to drupal payment status
    $eventType = $bodyArray['event_type'];
    switch ($eventType) {
      case 'PAYMENT.SALE.COMPLETED':
        $paymentStatusPluginId = 'payment_authorized';
        break;

      case 'PAYMENT.SALE.DENIED':
        $paymentStatusPluginId = 'payment_authorization_failed';
        break;

      case 'PAYMENT.SALE.PENDING':
        $paymentStatusPluginId = 'payment_pending';
        break;

      case 'PAYMENT.SALE.REFUNDED':
        $paymentStatusPluginId = 'payment_refunded';
        break;

      case 'PAYMENT.SALE.REVERSED':
        $paymentStatusPluginId = 'payment_refunded';
        break;

      default:
        // Throw exception if event_type as not been mapped
        throw new HttpException(500, 'Invalid payment status');
        break;
    }

    // Load payment status object from it's ID
    if (!$paymentStatus = Payment::statusManager()->createInstance($paymentStatusPluginId)) {
      throw new HttpException(500, 'Invalid plugin id');
    }

    // Update payment status and save entity.
    $this->payment->setPaymentStatus($paymentStatus);

    // This save can be hooked by the payment type to implement callback.
    $this->payment->save();

    // Return a 200 code for SystemPay back office.
    return new Response('Action successfully registered.', 200);
  }

}
