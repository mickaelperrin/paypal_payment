<?php

/**
 * @file
 * Definition of Drupal\paypal_payment\Entity\PayPalExpressProfile.
 */

namespace Drupal\paypal_payment\Entity;

/**
 * Defines a PayPal express profile entity.
 *
 * @ConfigEntityType(
 *   admin_permission = "administer paypal payment",
 *   handlers = {
 *     "access" = "\Drupal\Core\Entity\EntityAccessControlHandler",
 *     "form" = {
 *       "default" = "Drupal\paypal_payment\Form\PayPalExpressProfileForm",
 *       "delete" = "Drupal\paypal_payment\Form\PayPalDeleteForm"
 *     },
 *     "list_builder" = "Drupal\paypal_payment\Form\PayPalProfileListBuilder",
 *     "storage" = "\Drupal\Core\Config\Entity\ConfigEntityStorage"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid"
 *   },
 *   id = "paypal_express_profile",
 *   label = @Translation("PayPal Express Profile"),
 *   links = {
 *     "canonical" = "/admin/config/services/payment/paypal/profile/express/{paypal_express_profile}",
 *     "collection" = "/admin/config/services/payment/paypal/profiles/express",
 *     "edit-form" = "/admin/config/services/payment/paypal/profile/express/{paypal_express_profile}/edit",
 *     "delete-form" = "/admin/config/services/payment/paypal/profile/express/{paypal_express_profile}/delete"
 *   }
 * )
 */
class PayPalExpressProfile extends PayPalProfile implements PayPalExpressProfileInterface {

  /**
   * The PayPal username for express checkout.
   * @var string
   */
  protected $username;

  /**
   * The PayPal password for express checkout.
   * @var string
   */
  protected $password;

  /**
   * The PayPal signature for express checkout.
   * @var string
   */
  protected $signature;

  /**
   * @inheritdoc
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * @inheritdoc
   */
  public function setUsername(string $username) {
    $this->username = $username;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @inheritdoc
   */
  public function setPassword(string $password) {
    $this->password = $password;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getSignature() {
    return $this->signature;
  }

  /**
   * @inheritdoc
   */
  public function setSignature(string $signature) {
    $this->signature = $signature;
    return $this;
  }

}
