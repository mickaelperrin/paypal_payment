<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Redirect.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\payment\Entity\PaymentInterface;
use Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles the "redirect" route.
 */
class Redirect extends Base {

  /**
   * PayPal is redirecting the visitor here after the payment process. At this
   * point we don't know the status of the payment yet so we can only load
   * the payment and give control back to the payment context.
   *
   * @param PaymentInterface $payment
   * @return Response
   */
  public function execute(PaymentInterface $payment) {
    $request = \Drupal::request();
    $paymentId = $request->get('paymentId');
    $payerID = $request->get('PayerID');

    /** @var PayPalBasic $payment_method */
    $payment_method = $payment->getPaymentMethod();
    /** @var ApiContext $api_context */
    $api_context = $payment_method->getApiContext();

    $p = Payment::get($paymentId, $api_context);
    $execution = new PaymentExecution();
    $execution->setPayerId($payerID);
    try {
      $p->execute($execution, $api_context);
      $payment_method->doCapturePayment();
    } catch (\Exception $ex) {
      // TODO: Error handling
    }

    return $this->getResponse($payment);
  }

  public function cancel(PaymentInterface $payment) {
    return $this->getResponse($payment);
  }

  private function getResponse(PaymentInterface $payment) {
    $response = $payment->getPaymentType()->getResumeContextResponse();
    return $response->getResponse();
  }

}
