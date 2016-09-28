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
 *     "production",
 *     "clientId",
 *     "clientSecret",
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
   * The PayPal clientId for express checkout.
   * @var string
   */
  protected $clientId;

  /**
   * The PayPal password for express checkout.
   * @var string
   */
  protected $clientSecret;

  /**
   * @inheritdoc
   */
  public function getClientId() {
    return $this->clientId;
  }

  /**
   * @inheritdoc
   */
  public function setClientId(string $clientId) {
    $this->clientId = $clientId;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getClientSecret() {
    return $this->clientSecret;
  }

  /**
   * @inheritdoc
   */
  public function setClientSecret(string $clientSecret) {
    $this->clientSecret = $clientSecret;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getApiContext() {
    $apiContext = new ApiContext(
      new OAuthTokenCredential(
        $this->getClientId(),
        $this->getClientSecret()
      )
    );

    $apiContext->setConfig([
      'mode' => $this->isProduction() ? 'live' : 'sandbox',
      'log.LogEnabled' => FALSE,
      'cache.enabled' => FALSE,
    ]);

    return $apiContext;
  }

}
