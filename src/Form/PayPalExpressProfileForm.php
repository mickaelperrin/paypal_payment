<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Form\PayPalExpressProfileForm.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paypal_payment\Entity\PayPalExpressProfileInterface;
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
    /** @var PayPalExpressProfileInterface $paypal_profile */
    $paypal_profile = $this->getEntity();

    $form['clientId'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client ID'),
      '#default_value' => $paypal_profile->getClientId(),
      '#maxlength' => 255,
      '#required' => TRUE,
    ];
    $form['clientSecret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => $paypal_profile->getClientSecret(),
      '#maxlength' => 255,
      '#required' => TRUE,
    ];

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    $form_state->setRedirect('entity.paypal_express_profile.collection');
  }

}
