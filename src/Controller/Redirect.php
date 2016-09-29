<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Redirect.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\payment\Entity\PaymentInterface;
use Drupal\paypal_payment\Entity\PayPalProfileInterface;
use Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles the "redirect" route.
 */
class Redirect extends ControllerBase {

  public function access(PaymentInterface $payment) {
    $request = \Drupal::request();
    $paymentId = $request->get('paymentId');
    /** @var PayPalBasic $payment_method */
    $payment_method = $payment->getPaymentMethod();
    return AccessResult::allowedIf($payment_method->validatePaymentId($paymentId));
  }

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
    /** @var PayPalProfileInterface $profile */
    $profile = $payment_method->getProfile();

    $apiContext = $profile->getApiContext();

    $p = Payment::get($paymentId, $apiContext);
    $execution = new PaymentExecution();
    $execution->setPayerId($payerID);
    try {
      $p->execute($execution, $apiContext);
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
