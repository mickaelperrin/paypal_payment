<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic.
 */

namespace Drupal\paypal_payment\Plugin\Payment\Method;

use Drupal\Core\Url;
use Drupal\payment\OperationResult;
use Drupal\payment\Plugin\Payment\Method\Basic;
use Drupal\payment\Response\Response;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;

/**
 * Abstract class for PayPal payment methods.
 */
abstract class PayPalBasic extends Basic {

  const PAYPAL_CONTEXT_TYPE_ADMIN    = 'admin';
  const PAYPAL_CONTEXT_TYPE_CREATE   = 'create';
  const PAYPAL_CONTEXT_TYPE_WEBHOOK  = 'webhook';
  const PAYPAL_CONTEXT_TYPE_REDIRECT = 'redirect';

  /**
   * @return string
   */
  abstract public function getWebhookUrl();

  /**
   * @return string
   */
  abstract public function getWebhookId();

  /**
   * @param string $type
   * @return \PayPal\Rest\ApiContext
   */
  abstract public function getApiContext($type);

  private function setPaymentId($paymentId) {
    $this->configuration['paymentID'] = $paymentId;
    $this->getPayment()->save();
  }

  public function getPaymentId() {
    return isset($this->configuration['paymentID']) ? $this->configuration['paymentID'] : NULL;
  }

  /**
   * @inheritDoc
   */
  public function getPaymentExecutionResult() {
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
      ->setNotifyUrl($this->getWebhookUrl());

    $payment = new Payment();
    $payment->setIntent('sale')
      ->setPayer($payer)
      ->setRedirectUrls($redirectUrls)
      ->setTransactions([$transaction]);

    try {
      $payment->create($this->getApiContext(self::PAYPAL_CONTEXT_TYPE_CREATE));
      $this->setPaymentId($payment->getId());
      $url = Url::fromUri($payment->getApprovalLink());
      $response = new Response($url);
    } catch (\Exception $ex) {
      # TODO: clarify with the payment maintainer how we should handle Exceptions
      $response = NULL;
    }

    return new OperationResult($response);
  }

}
