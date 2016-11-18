<?php

/**
 * @file
 * Contains \Drupal\paypal_payment\Controller\Base.
 */

namespace Drupal\paypal_payment\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\payment\Entity\PaymentInterface;

/**
 * Abstract class for redirects and webhooks.
 */
abstract class Base extends ControllerBase {

  abstract protected function verify(PaymentInterface $payment);

  public function access(PaymentInterface $payment) {
    return AccessResult::allowedIf($this->verify($payment));
  }

}
