<?php

/**
 * @file
 * Definition of \Drupal\paypal_payment\Entity\PayPalProfileInterface.
 */

namespace Drupal\paypal_payment\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Defines PayPal profiles.
 */
interface PayPalProfileInterface extends ConfigEntityInterface {

  /**
   * @return string
   */
  public function getId();

  /**
   * @param string $id
   * @return PayPalProfile
   */
  public function setId(string $id);

  /**
   * @return string
   */
  public function getLabel();

  /**
   * @param string $label
   * @return PayPalProfile
   */
  public function setLabel(string $label);

  /**
   * @return string
   */
  public function getEmail();

  /**
   * @param string $email
   * @return PayPalProfile
   */
  public function setEmail(string $email);

  /**
   * @return boolean
   */
  public function isProduction();

  /**
   * @param boolean $production
   * @return PayPalProfile
   */
  public function setProduction(bool $production);

  /**
   * @return boolean
   */
  public function isAutocapture();

  /**
   * @param boolean $autocapture
   * @return PayPalProfile
   */
  public function setAutocapture(bool $autocapture);

}
