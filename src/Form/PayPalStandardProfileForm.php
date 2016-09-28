<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Form\PayPalStandardProfileForm.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paypal_payment\Entity\PayPalStandardProfileInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the PayPal standard profile add/edit form.
 */
class PayPalStandardProfileForm extends PayPalProfileForm {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = $container->get('entity.manager');

    return new static(
      $container->get('string_translation'),
      $entityTypeManager->getStorage('paypal_standard_profile'),
      $container->get('plugin.manager.plugin.plugin_selector'),
      $container->get('plugin.plugin_type_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var PayPalStandardProfileInterface $paypal_profile */
    $paypal_profile = $this->getEntity();

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => $paypal_profile->getEmail(),
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
    $form_state->setRedirect('entity.paypal_standard_profile.collection');
  }

}
