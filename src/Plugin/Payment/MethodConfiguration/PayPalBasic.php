<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\MethodConfiguration\PayPalBasic.
 */

namespace Drupal\paypal_payment\Plugin\Payment\MethodConfiguration;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\payment\Plugin\Payment\MethodConfiguration\Basic;
use Drupal\paypal_payment\Entity\PayPalProfile;

/**
 * Provides the configuration for the paypal_payment_basic payment method plugin.
 *
 * @PaymentMethodConfiguration(
 *   description = @Translation("PayPal payment method type."),
 *   id = "paypal_payment_basic",
 *   label = @Translation("PayPal")
 * )
 */
class PayPalBasic extends Basic {

  /**
   * Gets the PayPal profile.
   *
   * @return string
   */
  public function getProfile() {
    return isset($this->configuration['profile']) ? $this->configuration['profile'] : '';
  }

  /**
   * Implements a form API #process callback.
   */
  public function processBuildConfigurationForm(array &$element, FormStateInterface $form_state, array &$form) {
    parent::processBuildConfigurationForm($element, $form_state, $form);

    $element['profile'] = [
      '#type' => 'select',
      '#title' => $this->t('PayPal profile'),
      '#options' => PayPalProfile::loadAllForSelect(),
      '#default_value' => $this->getProfile(),
      '#required' => TRUE,
      '#description' => $this->t('The PayPal profile that will be used to connect to PayPal.'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $parents = $form['plugin_form']['profile']['#parents'];
    array_pop($parents);
    $values = $form_state->getValues();
    $values = NestedArray::getValue($values, $parents);
    $this->configuration['profile'] = $values['profile'];
  }

}
