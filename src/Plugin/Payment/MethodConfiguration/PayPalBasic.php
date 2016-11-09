<?php

/**
 * Contains \Drupal\paypal_payment\Plugin\Payment\MethodConfiguration\PayPalBasic.
 */

namespace Drupal\paypal_payment\Plugin\Payment\MethodConfiguration;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\payment\Plugin\Payment\MethodConfiguration\Basic;

/**
 * Abstract class for PayPal payment method configurations.
 */
abstract class PayPalBasic extends Basic {

  /**
   * Gets the setting for the production server.
   *
   * @return bool
   */
  public function isProduction() {
    return !empty($this->configuration['production']);
  }

  /**
   * Implements a form API #process callback.
   */
  public function processBuildConfigurationForm(array &$element, FormStateInterface $form_state, array &$form) {
    parent::processBuildConfigurationForm($element, $form_state, $form);

    $element['paypal'] = [
      '#type' => 'container',
    ];
    $element['paypal']['production'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Production Server'),
      '#default_value' => $this->isProduction(),
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
    $this->configuration['production'] = !empty($values['production']);
  }

  /**
   * @return array
   */
  public function getDerivativeConfiguration() {
    return [
      'production' => $this->isProduction(),
    ];
  }

}
