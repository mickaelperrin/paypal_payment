<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Form\PayPalProfileListBuilder.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\paypal_payment\Entity\PayPalExpressProfileInterface;
use Drupal\paypal_payment\Entity\PayPalStandardProfileInterface;

/**
 * Lists paypal_profile entities.
 */
class PayPalProfileListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['name'] = ($this->entityTypeId == 'paypal_standard_profile') ? $this->t('Email') : $this->t('Client ID');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    if ($entity instanceof PayPalStandardProfileInterface) {
      $name = $entity->getEmail();
    }
    else if ($entity instanceof PayPalExpressProfileInterface) {
      $name = $entity->getClientId();
    }
    else {
      $name = '';
    }
    $row['label'] = $entity->label();
    $row['name'] = $name;

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
