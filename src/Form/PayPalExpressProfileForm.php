<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Form\PayPalExpressProfileForm.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paypal_payment\Entity\PayPalExpressProfileInterface;
use Drupal\paypal_payment\Entity\PayPalProfileInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the PayPal express profile add/edit form.
 */
class PayPalExpressProfileForm extends PayPalProfileForm {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = $container->get('entity.manager');

    return new static(
      $container->get('string_translation'),
      $entityTypeManager->getStorage('paypal_express_profile'),
      $container->get('plugin.manager.plugin.plugin_selector'),
      $container->get('plugin.plugin_type_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['email']['#required'] = FALSE;

    /** @var PayPalExpressProfileInterface $paypal_profile */
    $paypal_profile = $this->getEntity();

    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $paypal_profile->getUsername(),
      '#maxlength' => 255,
      '#required' => TRUE,
    );
    $form['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#default_value' => $paypal_profile->getPassword(),
      '#maxlength' => 255,
      '#required' => TRUE,
    );
    $form['signature'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Signature'),
      '#default_value' => $paypal_profile->getSignature(),
      '#maxlength' => 255,
      '#required' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function copyFormValuesToEntity(EntityInterface $paypal_profile, array $form, FormStateInterface $form_state) {
    /** @var PayPalExpressProfileInterface $paypal_profile */
    parent::copyFormValuesToEntity($paypal_profile, $form, $form_state);
    $values = $form_state->getValues();
    $paypal_profile->setUsername($values['username']);
    $paypal_profile->setPassword($values['password']);
    $paypal_profile->setSignature($values['signature']);
  }

}
