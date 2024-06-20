<?php

namespace Drupal\supabase_authentication\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SupabaseAuthSettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['supabase_authentication.settings'];
  }

  public function getFormId() {
    return 'supabase_authentication_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('supabase_authentication.settings');

    $form['supabase_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Project URL'),
      '#default_value' => $config->get('supabase_url'),
      '#required' => TRUE,
    ];
    $form['supabase_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Project API Key (service_role)'),
      '#default_value' => $config->get('supabase_key'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('supabase_authentication.settings')
      ->set('supabase_url', $form_state->getValue('supabase_url'))
      ->set('supabase_key', $form_state->getValue('supabase_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
