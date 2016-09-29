<?php

/**
 * @file
 * Definition of \Drupal\paypal_payment\Entity\PayPalProfileInterface.
 */

namespace Drupal\paypal_payment\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use PayPal\Rest\ApiContext;

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
   * @return boolean
   */
  public function isProduction();

  /**
   * @param boolean $production
   * @return PayPalProfile
   */
  public function setProduction(bool $production);

  /**
   * Helper method for getting an APIContext for all calls
   *
   * @return ApiContext
   */
  public function getApiContext();

  /**
   * @param string $id
   * @return PayPalProfileInterface
   */
  public static function loadOne($id);

  /**
   * @return PayPalProfileInterface[]
   */
  public static function loadAll();

  /**
   * @param bool $includeNone
   * @return array
   */
  public static function loadAllForSelect($includeNone = TRUE);

}
