<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Form\PayPalProfileDeleteForm.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the PayPal profile deletion form.
 */
class PayPalProfileDeleteForm extends EntityConfirmFormBase {

  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new instance.
   *
   * @param TranslationInterface $string_translation
   *   The string translator.
   * @param LoggerInterface $logger
   */
  public function __construct(TranslationInterface $string_translation, LoggerInterface $logger) {
    $this->logger = $logger;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('string_translation'), $container->get('paypal_payment.logger'));
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Do you really want to delete %label?', array(
      '%label' => $this->getEntity()->label(),
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return $this->getEntity()->toUrl('collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->getEntity()->delete();
    $this->logger->info('PayPal profile %label (@id) has been deleted.', [
      '@id' => $this->getEntity()->id(),
      '%label' => $this->getEntity()->label(),
    ]);
    drupal_set_message($this->t('%label has been deleted.', array(
      '%label' => $this->getEntity()->label(),
    )));
    $form_state->setRedirectUrl($this->getEntity()->toUrl('collection'));
  }
}
