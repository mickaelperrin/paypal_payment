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
   * @inheritDoc
   */
  public function getPaymentExecutionResult() {
    /** @var PayPalProfileInterface $profile */
    $profile = PayPalProfile::loadOne($this->pluginDefinition['profile']);

    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

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

    $redirect = new Url('paypal_payment.redirect',
      array('payment' => $this->getPayment()->id()), array('absolute' => TRUE));
    $redirectUrl = $redirect->toString(TRUE)->getGeneratedUrl();
    $webhookUrl = new Url('paypal_payment.webhook',
      array('payment' => $this->getPayment()->id()), array('absolute' => TRUE));

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($redirectUrl)
      ->setCancelUrl($redirectUrl);

    $amount = new Amount();
    $amount->setCurrency("USD")
      ->setTotal($totalAmount);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
      ->setItemList($itemList)
      ->setDescription($this->getPayment()->id())
      ->setInvoiceNumber($this->getPayment()->id());

    $payment = new Payment();
    $payment->setIntent("sale")
      ->setPayer($payer)
      ->setRedirectUrls($redirectUrls)
      ->setTransactions(array($transaction));

    try {
      $payment->create($profile->getApiContext());
    } catch (\Exception $ex) {
      // TODO: Error handling
      exit;
    }

    $link = $payment->getApprovalLink();
    $links = $payment->getLinks();
    $url = Url::fromUri($link);
    $response = new Response($url);
    return new OperationResult($response);
  }

}
