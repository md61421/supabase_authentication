<?php

namespace Drupal\supabase_authentication\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use PHPSupabase\Service;

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

    // Load Supabase configuration
    $config = \Drupal::config('supabase_authentication.settings');
    $supabase_url = $config->get('supabase_url');
    $supabase_key = $config->get('supabase_key');

    // Debugging: log the URL and API key
    \Drupal::logger('supabase_authentication')->info('Supabase URL: @url, Supabase Key: @key', ['@url' => $supabase_url, '@key' => $supabase_key]);

    // Initialize Supabase Service
    $service = new Service($supabase_key, $supabase_url . '/auth/v1');
    $auth = $service->createAuth();

    try {
      // Create user in Supabase
      $auth->createUserWithEmailAndPassword($email, $password);
      $data = $auth->data();

      // Check for errors
      if (isset($data->id)) {
        \Drupal::messenger()->addMessage($this->t('User registration successful. Please check your email for confirmation.'));
      } else {
        \Drupal::messenger()->addError($this->t('User registration failed. Please try again.'));
      }
    } catch (\Exception $e) {
      \Drupal::messenger()->addError($this->t('An error occurred: @message', ['@message' => $e->getMessage()]));
    }
  }
}
