<?php

namespace Drupal\supabase_authentication\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SupabaseRegisterForm extends FormBase {
  public function getFormId() {
    return 'supabase_register_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $password = $form_state->getValue('password');
    $url = '/supabase-authentication/register?email=' . $email . '&password=' . $password;
    $response = new \Symfony\Component\HttpFoundation\RedirectResponse($url);
    $response->send();
  }
}