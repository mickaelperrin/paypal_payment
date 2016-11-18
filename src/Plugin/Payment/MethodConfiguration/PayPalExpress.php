<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\MethodConfiguration\PayPalExpress.
 */

namespace Drupal\paypal_payment\Plugin\Payment\MethodConfiguration;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paypal_payment\Plugin\Payment\Method\PayPalExpress as PayPalExpressMethod;
use PayPal\Api\Webhook;
use PayPal\Api\WebhookEventType;

/**
 * Provides the configuration for the PayPal Express payment method plugin.
 *
 * @PaymentMethodConfiguration(
 *   description = @Translation("PayPal Express payment method type."),
 *   id = "paypal_payment_express",
 *   label = @Translation("PayPal Express")
 * )
 */
class PayPalExpress extends PayPalBasic {

  /**
   * Gets the client ID of this configuration.
   *
   * @return string
   */
  public function getClientId() {
    return isset($this->configuration['clientId']) ? $this->configuration['clientId'] : '';
  }

  /**
   * Gets the client secret of this configuration.
   *
   * @return string
   */
  public function getClientSecret() {
    return isset($this->configuration['clientSecret']) ? $this->configuration['clientSecret'] : '';
  }

  /**
   * Gets the webhook ID of this configuration.
   *
   * @return string
   */
  public function getWebhookId() {
    return isset($this->configuration['webhookId']) ? $this->configuration['webhookId'] : '';
  }

  /**
   * Implements a form API #process callback.
   */
  public function processBuildConfigurationForm(array &$element, FormStateInterface $form_state, array &$form) {
    parent::processBuildConfigurationForm($element, $form_state, $form);

    $element['paypal']['clientId'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client ID'),
      '#default_value' => $this->getClientId(),
      '#maxlength' => 255,
      '#required' => TRUE,
    ];
    $element['paypal']['clientSecret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => $this->getClientSecret(),
      '#maxlength' => 255,
      '#required' => TRUE,
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $parents = $form['plugin_form']['paypal']['#parents'];
    array_pop($parents);
    $values = $form_state->getValues();
    $values = NestedArray::getValue($values, $parents);
    $this->configuration['clientId'] = $values['paypal']['clientId'];
    $this->configuration['clientSecret'] = $values['paypal']['clientSecret'];
    $this->configuration['webhookId'] = $this->updateWebhook($this->configuration, $form_state->getValue('id'));
  }

  private function updateWebhook($configuration, $id) {
    $webhookId = $this->getWebhookId();
    $apiContext = PayPalExpressMethod::apiContext($configuration, PayPalExpressMethod::PAYPAL_CONTEXT_TYPE_ADMIN);
    if (!empty($webhookId)) {
      try {
        $webhook = Webhook::get($webhookId, $apiContext);
        $webhookId = $webhook->getId();
      } catch (\Exception $ex) {}
    }

    if (empty($webhookId)) {
      try {
        // Create a new webhook
        $webhook = new Webhook();
        $webhook->setUrl(PayPalExpressMethod::webhookUrl($id));
        $eventTypes = WebhookEventType::availableEventTypes($apiContext);
        $webhook->setEventTypes($eventTypes->getEventTypes());
        $webhook = $webhook->create($apiContext);
        $webhookId = $webhook->getId();
      } catch (\Exception $ex) {}
    }
    return $webhookId;
  }

  /**
   * @inheritDoc
   */
  public function getDerivativeConfiguration() {
    return parent::getDerivativeConfiguration() + [
      'clientId' => $this->getClientId(),
      'clientSecret' => $this->getClientSecret(),
      'webhookId' => $this->getWebhookId(),
    ];
  }

}
