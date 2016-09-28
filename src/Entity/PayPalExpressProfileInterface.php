<?php

/**
 * @file
 * Definition of \Drupal\paypal_payment\Entity\PayPalExpressProfileInterface.
 */

namespace Drupal\paypal_payment\Entity;

/**
 * Defines PayPal Express profiles.
 */
interface PayPalExpressProfileInterface extends PayPalProfileInterface {

  /**
   * @return string
   */
  public function getClientId();

  /**
   * @param string $clientId
   * @return PayPalExpressProfile
   */
  public function setClientId(string $clientId);

  /**
   * @return string
   */
  public function getClientSecret();

  /**
   * @param string $clientSecret
   * @return PayPalExpressProfile
   */
  public function setClientSecret(string $clientSecret);

}
