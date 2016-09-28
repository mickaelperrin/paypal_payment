<?php

/**
 * @file
 * Definition of \Drupal\paypal_payment\Entity\PayPalStandardProfileInterface.
 */

namespace Drupal\paypal_payment\Entity;

/**
 * Defines PayPal Standard profiles.
 */
interface PayPalStandardProfileInterface extends PayPalProfileInterface {

  /**
   * @return string
   */
  public function getEmail();

  /**
   * @param string $email
   * @return PayPalStandardProfile
   */
  public function setEmail(string $email);

}
