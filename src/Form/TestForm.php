<?php
/**
 * @file
 * Contains \Drupal\paypal_payment\Form\TestForm.
 */

namespace Drupal\paypal_payment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\paypal_payment\Entity\PayPalProfile;
use Drupal\paypal_payment\Entity\PayPalProfileInterface;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

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
    /** @var PayPalProfileInterface $profile */
    $profile = PayPalProfile::loadOne($form_state->getValue('profile'));
    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $redirect = new Url('paypal_payment.redirect',
      ['payment' => 1], ['absolute' => TRUE]);
    $redirectUrl = $redirect->toString(TRUE)->getGeneratedUrl();
    $webhookUrl = new Url('paypal_payment.webhook',
      ['payment' => 1], ['absolute' => TRUE]);
    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($redirectUrl)
      ->setCancelUrl($redirectUrl);
    $amount = new Amount();
    $amount->setCurrency('USD')
      ->setTotal(5);
    $transaction = new Transaction();
    $transaction->setAmount($amount)
      ->setDescription(1)
      ->setInvoiceNumber(1);
    $payment = new Payment();
    $payment->setIntent('sale')
      ->setPayer($payer)
      ->setRedirectUrls($redirectUrls)
      ->setTransactions([$transaction]);
    try {
      $payment->create($profile->getApiContext());
      drupal_set_message('OK');
    } catch (\Exception $ex) {
      drupal_set_message(t('Failing: @message', ['@message' => $ex->getMessage()]), 'error');
    }
  }
}
