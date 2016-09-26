<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Form\PayPalProfileListBuilder.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\paypal_payment\Entity\PayPalProfile;

/**
 * Lists paypal_profile entities.
 */
class PayPalProfileListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['email'] = $this->t('Email');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var PayPalProfile $entity */
    // Label
    $row['label'] = $entity->label();

    // API key
    $row['email'] = $entity->getEmail();

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();

    $build['#empty'] = $this->t('There are no PayPal profiles configured yet.');

    return $build;
  }
}
