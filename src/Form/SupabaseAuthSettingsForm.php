<?php

namespace Drupal\supabase_authentication\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a configuration form for Supabase authentication settings.
 */
class SupabaseAuthSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['supabase_authentication.config_form'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'supabase_authentication_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('supabase_authentication.config_form');

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Project URL'),
      '#default_value' => $config->get('url'),
      '#required' => TRUE,
    ];

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Project API Key (service_role)'),
      '#default_value' => $config->get('api_key'),
      '#required' => TRUE,
      '#maxlength' => 256,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    if (!filter_var($form_state->getValue('url'), FILTER_VALIDATE_URL)) {
      $form_state->setErrorByName('url', $this->t('The Supabase URL is not a valid URL.'));
    }

    if (empty($form_state->getValue('api_key'))) {
      $form_state->setErrorByName('api_key', $this->t('The API key cannot be empty.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('supabase_authentication.config_form')
      ->set('url', $form_state->getValue('url'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}

