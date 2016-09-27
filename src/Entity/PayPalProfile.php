<?php

/**
 * @file
 * Definition of Drupal\paypal_payment\Entity\PayPalProfile.
 */

namespace Drupal\paypal_payment\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines a PayPal profile entity.
 */
abstract class PayPalProfile extends ConfigEntityBase implements PayPalProfileInterface {

  /**
   * The entity's unique machine name.
   * @var string
   */
  protected $id;

  /**
   * The human-readable name.
   * @var string
   */
  protected $label;

  /**
   * The PayPal account email address.
   * @var string
   */
  protected $email;

  /**
   * Flag if the production server will be used, otherwise sandbox.
   * @var bool
   */
  protected $production;

  /**
   * Flag if automatic capture is enabled.
   * @var bool
   */
  protected $autocapture;

  /**
   * @inheritdoc
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @inheritdoc
   */
  public function setId(string $id) {
    $this->id = $id;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * @inheritdoc
   */
  public function setLabel(string $label) {
    $this->label = $label;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @inheritdoc
   */
  public function setEmail(string $email) {
    $this->email = $email;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function isProduction() {
    return $this->production;
  }

  /**
   * @inheritdoc
   */
  public function setProduction(bool $production) {
    $this->production = $production;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function isAutocapture() {
    return $this->autocapture;
  }

  /**
   * @inheritdoc
   */
  public function setAutocapture(bool $autocapture) {
    $this->autocapture = $autocapture;
    return $this;
  }

}
