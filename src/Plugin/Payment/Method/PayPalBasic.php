<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic.
 */

namespace Drupal\paypal_payment\Plugin\Payment\Method;

use Drupal\Core\Url;
use Drupal\payment\OperationResult;
use Drupal\payment\Plugin\Payment\Method\Basic;
use Drupal\payment\Response\Response;
use Drupal\paypal_payment\Entity\PayPalProfile;
use Drupal\paypal_payment\Entity\PayPalProfileInterface;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

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
   * Get the PayPal profile to use.
   *
   * @return PayPalProfileInterface
   */
  public function getProfile() {
    return PayPalProfile::loadOne($this->pluginDefinition['profile']);
  }

  public function validatePaymentId($paymentId) {
    return ($paymentId === $this->configuration['paymentID']);
  }

  private function setPaymentId($paymentId) {
    $this->configuration['paymentID'] = $paymentId;
    $this->getPayment()->save();
  }

  /**
   * @inheritDoc
   */
  public function getPaymentExecutionResult() {
    /** @var PayPalProfileInterface $profile */
    $profile = $this->getProfile();

    $payer = new Payer();
    $payer->setPaymentMethod('paypal');

    $itemList = new ItemList();
    $totalAmount = 0;
    foreach ($this->getPayment()->getLineItems() as $line_item) {
      $totalAmount += $line_item->getTotalAmount();

      $item = new Item();
      $item->setName($line_item->getName())
        ->setCurrency($line_item->getCurrencyCode())
        ->setQuantity($line_item->getQuantity())
        ->setPrice($line_item->getTotalAmount());
      $itemList->addItem($item);
    }

    $redirectSuccess = new Url('paypal_payment.redirect.success',
      ['payment' => $this->getPayment()->id()], ['absolute' => TRUE]);
    $redirectCancel = new Url('paypal_payment.redirect.cancel',
      ['payment' => $this->getPayment()->id()], ['absolute' => TRUE]);
    $webhook = new Url('paypal_payment.webhook',
      ['payment' => $this->getPayment()->id()], ['absolute' => TRUE]);
    $webhoookUrl = $webhook->toString(TRUE)->getGeneratedUrl();

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($redirectSuccess->toString(TRUE)->getGeneratedUrl())
      ->setCancelUrl($redirectCancel->toString(TRUE)->getGeneratedUrl());

    $amount = new Amount();
    $amount->setCurrency('USD')
      ->setTotal($totalAmount);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
      ->setItemList($itemList)
      ->setDescription($this->getPayment()->id())
      ->setInvoiceNumber($this->getPayment()->id())
      ->setNotifyUrl($webhoookUrl);

    $payment = new Payment();
    $payment->setIntent('sale')
      ->setPayer($payer)
      ->setRedirectUrls($redirectUrls)
      ->setTransactions([$transaction]);

    try {
      $payment->create($profile->getApiContext());
      $this->setPaymentId($payment->getId());
    } catch (\Exception $ex) {
      // TODO: Error handling
      exit;
    }

    $url = Url::fromUri($payment->getApprovalLink());
    $response = new Response($url);
    return new OperationResult($response);
  }

}
