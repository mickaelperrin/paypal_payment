<?php
/**
 * @file
 * Contains \Drupal\paypal_payment\Form\TestForm.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paypal_payment\Entity\PayPalProfile;
use Drupal\paypal_payment\Entity\PayPalProfileInterface;

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
    $paypal_profiles = PayPalProfile::loadMultiple();

    $options = array('' => t('- Select a profile -'));
    foreach($paypal_profiles as $id => $paypal_profile) {
      /** @var PayPalProfileInterface $paypal_profile */
      $options[$paypal_profile->getEmail()] = $paypal_profile->label();
    }

    $form['email'] = array(
      '#title' => t('PayPal profile'),
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => '',
    );

    $form['actions'] = array(
      'submit' => array(
        '#type' => 'submit',
        '#value' => t('Test'),
      )
    );

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
