<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\Method\PayPalBasic.
 */

namespace Drupal\paypal_payment\Plugin\Payment\Method;

use Drupal\Core\Url;
use Drupal\payment\OperationResult;
use Drupal\payment\Plugin\Payment\Method\Basic;
use Drupal\payment\Response\Response;
use Drupal\paypal_payment\Entity\PayPalExpressProfile;
use Drupal\paypal_payment\Entity\PayPalStandardProfile;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

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
    #$profile_id = $this->pluginDefinition['profile'];
    #$profile = PayPalStandardProfile::load($profile_id);

    $payer = new Payer();
    $payer->setPaymentMethod("paypal");
    $itemList = new ItemList();

    $amnt = 0;
    foreach ($this->getPayment()->getLineItems() as $line_item) {
      $amnt += $line_item->getTotalAmount();

      $item = new Item();
      $item->setName($line_item->getName())
        ->setCurrency($line_item->getCurrencyCode())
        ->setQuantity($line_item->getQuantity())
        ->setPrice($line_item->getTotalAmount());
      $itemList->addItem($item);
    }

    $redirectUrl = new Url('paypal_payment.redirect',
      array('payment' => $this->getPayment()->id()), array('absolute' => TRUE));
    $webhookUrl = new Url('paypal_payment.webhook',
      array('payment' => $this->getPayment()->id()), array('absolute' => TRUE));

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($redirectUrl->toString(TRUE)->getGeneratedUrl())
      ->setCancelUrl($redirectUrl->toString(TRUE)->getGeneratedUrl());

    $amount = new Amount();
    $amount->setCurrency("USD")
      ->setTotal($amnt);

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

    $clientId = '';
    $clientSecret = '';
    try {
      $payment->create($this->getApiContext($clientId, $clientSecret));
    } catch (\Exception $ex) {
      // TODO: Error handling
      exit;
    }

    $url = Url::fromUri($payment->getApprovalLink());
    $response = new Response($url);
    return new OperationResult($response);
  }

  /**
   * Helper method for getting an APIContext for all calls
   * @param string $clientId Client ID
   * @param string $clientSecret Client Secret
   * @return ApiContext
   */
  private function getApiContext($clientId, $clientSecret)
  {

    // #### SDK configuration
    // Register the sdk_config.ini file in current directory
    // as the configuration source.
    /*
    if(!defined("PP_CONFIG_PATH")) {
        define("PP_CONFIG_PATH", __DIR__);
    }
    */


    // ### Api context
    // Use an ApiContext object to authenticate
    // API calls. The clientId and clientSecret for the
    // OAuthTokenCredential class can be retrieved from
    // developer.paypal.com

    $apiContext = new ApiContext(
      new OAuthTokenCredential(
        $clientId,
        $clientSecret
      )
    );

    // Comment this line out and uncomment the PP_CONFIG_PATH
    // 'define' block if you want to use static file
    // based configuration

    $apiContext->setConfig(
      array(
        'mode' => 'sandbox',
        'log.LogEnabled' => true,
        'log.FileName' => '/tmp/PayPal.log',
        'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
        'cache.enabled' => false,
        // 'http.CURLOPT_CONNECTTIMEOUT' => 30
        // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
        //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
      )
    );

    // Partner Attribution Id
    // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
    // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
    // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');

    return $apiContext;
  }

}
