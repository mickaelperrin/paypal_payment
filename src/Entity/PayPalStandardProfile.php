<?php

/**
 * @file
 * Definition of Drupal\paypal_payment\Entity\PayPalStandardProfile.
 */

namespace Drupal\paypal_payment\Entity;

/**
 * Defines a PayPal standard profile entity.
 *
 * @ConfigEntityType(
 *   admin_permission = "paypal_payment.administer",
 *   handlers = {
 *     "access" = "\Drupal\Core\Entity\EntityAccessControlHandler",
 *     "form" = {
 *       "default" = "\Drupal\paypal_payment\Form\PayPalStandardProfileForm",
 *       "delete" = "\Drupal\paypal_payment\Form\PayPalDeleteForm"
 *     },
 *     "list_builder" = "\Drupal\paypal_payment\Form\PayPalProfileListBuilder",
 *     "storage" = "\Drupal\Core\Config\Entity\ConfigEntityStorage"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "uuid",
 *     "id",
 *     "label",
 *     "production",
 *     "email",
 *   },
 *   id = "paypal_standard_profile",
 *   label = @Translation("PayPal Standard Profile"),
 *   links = {
 *     "canonical" = "/admin/config/services/payment/paypal/profile/standard/{paypal_standard_profile}",
 *     "collection" = "/admin/config/services/payment/paypal/profiles/standard",
 *     "edit-form" = "/admin/config/services/payment/paypal/profile/standard/{paypal_standard_profile}/edit",
 *     "delete-form" = "/admin/config/services/payment/paypal/profile/standard/{paypal_standard_profile}/delete"
 *   }
 * )
 */
class PayPalStandardProfile extends PayPalProfile implements PayPalStandardProfileInterface {

  /**
   * The PayPal email address.
   * @var string
   */
  protected $email;

  /**
   * @inheritDoc
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @inheritDoc
   */
  public function setEmail(string $email) {
    $this->email = $email;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getApiContext() {
    // TODO: Implement getApiContext() method.
  }

}
