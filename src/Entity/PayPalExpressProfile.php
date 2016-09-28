<?php

/**
 * @file
 * Definition of Drupal\paypal_payment\Entity\PayPalExpressProfile.
 */

namespace Drupal\paypal_payment\Entity;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

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
 *     "uuid",
 *     "id",
 *     "label",
 *     "email",
 *     "production",
 *     "autocapture",
 *     "username",
 *     "password",
 *     "signature",
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

  /**
   * @inheritdoc
   */
  public function getApiContext() {
    $apiContext = new ApiContext(
      new OAuthTokenCredential(
        $this->getUsername(),
        $this->getPassword()
      )
    );

    $apiContext->setConfig(
      array(
        'mode' => 'sandbox',
        'log.LogEnabled' => TRUE,
        'log.FileName' => '/tmp/PayPal.log',
        'log.LogLevel' => 'DEBUG',
        'cache.enabled' => FALSE,
      )
    );

    return $apiContext;
  }

}
