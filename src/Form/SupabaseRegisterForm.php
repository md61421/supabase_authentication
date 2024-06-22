<?php

namespace Drupal\supabase_authentication\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use PHPSupabase\Service;

class SupabaseRegisterForm extends FormBase {

  // Function to return the form ID
  public function getFormId() {
    return 'supabase_register_form';
  }

  // Function to build the form
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Email field
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    
    // Password field
    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
    ];
    
    // Submit button
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];

    return $form;
  }

  // Function to handle form submission
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get the email and password from the form submission
    $email = $form_state->getValue('email');
    $password = $form_state->getValue('password');

    // Check if the email already exists in the supabase_users table
    $connection = \Drupal::database();
    $query = $connection->select('supabase_users', 'u')
      ->fields('u', ['email'])
      ->condition('email', $email)
      ->execute();
    $existing_user = $query->fetchField();

    if ($existing_user) {
      // Email already exists
      \Drupal::messenger()->addError($this->t('This email is already registered. Please use a different email.'));
    } else {
      // Load Supabase configuration
      $config = \Drupal::config('supabase_authentication.settings');
      $supabase_url = $config->get('supabase_url');
      $supabase_key = $config->get('supabase_key');

      // Initialize Supabase service
      $service = new Service($supabase_key, $supabase_url . '/auth/v1');
      $auth = $service->createAuth();

      try {
        // Create user in Supabase
        $auth->createUserWithEmailAndPassword($email, $password);
        $data = $auth->data();

        // If user creation is successful
        if (isset($data->id)) {
          // Save user details in Drupal database
          $connection->insert('supabase_users')
            ->fields([
              'email' => $email,
            ])
            ->execute();

          \Drupal::messenger()->addMessage($this->t('User registration successful. Please check your email for confirmation.'));
        } else {
          \Drupal::messenger()->addError($this->t('User registration failed. Please try again.'));
        }
      } catch (\Exception $e) {
        \Drupal::messenger()->addError($this->t('An error occurred: @message', ['@message' => $e->getMessage()]));
      }
    }
  }
}
