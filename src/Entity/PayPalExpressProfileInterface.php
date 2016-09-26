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
  public function getUsername();

  /**
   * @param string $username
   * @return PayPalExpressProfile
   */
  public function setUsername(string $username);

  /**
   * @return string
   */
  public function getPassword();

  /**
   * @param string $password
   * @return PayPalExpressProfile
   */
  public function setPassword(string $password);

  /**
   * @return string
   */
  public function getSignature();

  /**
   * @param string $signature
   * @return PayPalExpressProfile
   */
  public function setSignature(string $signature);

}
