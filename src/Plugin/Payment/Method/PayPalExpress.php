<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\Method\PayPalExpress.
 */

namespace Drupal\paypal_payment\Plugin\Payment\Method;

use Drupal\Core\PhpStorage\PhpStorageFactory;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

/**
 * PayPal Express payment method.
 *
 * @PaymentMethod(
 *   deriver = "\Drupal\paypal_payment\Plugin\Payment\Method\PayPalExpressDeriver",
 *   id = "paypal_payment_express",
 *   operations_provider = "\Drupal\paypal_payment\Plugin\Payment\Method\PayPalExpressOperationsProvider",
 * )
 */
class PayPalExpress extends PayPalBasic {

  /**
   * {@inheritdoc}
   */
  public function getApiContext() {
    $configuration = $this->getPluginDefinition();
    $apiContext = new ApiContext(
      new OAuthTokenCredential(
        $configuration['clientId'],
        $configuration['clientSecret']
      )
    );

    // TODO: Test caching
    $storage = PhpStorageFactory::get('paypal_api_context');
    if (!$storage->exists('auth.cache')) {
      $storage->save('auth.cache', '');
    }

    // TODO: Make logging configurable
    $apiContext->setConfig([
      'mode' => $configuration['production'] ? 'live' : 'sandbox',
      'log.LogEnabled' => TRUE,
      'log.FileName' => '/tmp/PayPal.log',
      'log.LogLevel' => 'DEBUG',
      'cache.enabled' => TRUE,
      'cache.FileName' => DRUPAL_ROOT . '/' . $storage->getFullPath('auth.cache'),
    ]);

    return $apiContext;
  }

}
