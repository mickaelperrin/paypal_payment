<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Form\PayPalProfileForm.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\paypal_payment\Entity\PayPalProfileInterface;
use Drupal\plugin\Plugin\Plugin\PluginSelector\PluginSelectorManagerInterface;
use Drupal\plugin\PluginType\PluginTypeManager;

/**
 * Provides the PayPal profile add/edit form.
 */
abstract class PayPalProfileForm extends EntityForm {

  /**
   * The PayPal profile storage.
   *
   * @var EntityStorageInterface
   */
  protected $paypalProfileStorage;

  /**
   * The plugin selector manager.
   *
   * @var PluginSelectorManagerInterface
   */
  protected $pluginSelectorManager;

  /**
   * The plugin type manager.
   *
   * @var PluginTypeManager
   */
  protected $pluginTypeManager;

  /**
   * Constructs a new instance.
   *
   * @param TranslationInterface $string_translation
   *   The string translator.
   * @param EntityStorageInterface $paypal_profile_storage
   *   The PayPal profile storage.
   * @param PluginSelectorManagerInterface $plugin_selector_manager
   *   The plugin selector manager.
   * @param PluginTypeManager $plugin_type_manager
   *   The plugin type manager.
   */
  public function __construct(TranslationInterface $string_translation, EntityStorageInterface $paypal_profile_storage, PluginSelectorManagerInterface $plugin_selector_manager, PluginTypeManager $plugin_type_manager) {
    $this->paypalProfileStorage = $paypal_profile_storage;
    $this->pluginSelectorManager = $plugin_selector_manager;
    $this->pluginTypeManager = $plugin_type_manager;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var PayPalProfileInterface $paypal_profile */
    $paypal_profile = $this->getEntity();
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $paypal_profile->label(),
      '#maxlength' => 255,
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#default_value' => $paypal_profile->id(),
      '#disabled' => !$paypal_profile->isNew(),
      '#machine_name' => [
        'source' => ['label'],
        'exists' => [$this, 'PayPalProfileIdExists'],
      ],
      '#maxlength' => 255,
      '#type' => 'machine_name',
      '#required' => TRUE,
    ];
    $form['production'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Production Server'),
      '#default_value' => $paypal_profile->isProduction(),
    ];

    return parent::form($form, $form_state);
  }

  /**
   * Checks if a PayPal profile with a particular ID already exists.
   *
   * @param string $id
   *
   * @return bool
   */
  public function paypalProfileIdExists($id) {
    return (bool) $this->paypalProfileStorage->load($id);
  }

}
