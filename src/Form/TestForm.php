<?php
/**
 * @file
 * Contains \Drupal\paypal_payment\Form\TestForm.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paypal_payment\Entity\PayPalProfile;

/**
 * TBD.
 */
class TestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'paypal_payment_test';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['profile'] = [
      '#title' => t('PayPal profile'),
      '#type' => 'select',
      '#options' => PayPalProfile::loadAllForSelect(),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      'submit' => [
        '#type' => 'submit',
        '#value' => t('Test'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: perform the test and return to a reasonable site
  }
}

?>
