<?php

namespace Drupal\supabase_authentication;

use PHPSupabase\Service;
use Drupal\Core\Config\ConfigFactoryInterface;

class SupabaseService {

  protected $configFactory;
  protected $supabaseService;

  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    $config = $this->configFactory->get('supabase_authentication.config_form');

    $apiKey = $config->get('api_key');
    $url = $config->get('url');
    $this->supabaseService = new Service($apiKey, $url);
  }

  public function createUser($email, $password, $metadata = []) {
    $auth = $this->supabaseService->createAuth();
    try {
      $auth->createUserWithEmailAndPassword($email, $password, $metadata);
      return $auth->data();
    } catch (\Exception $e) {
      return $auth->getError();
    }
  }

  public function signInUser($email, $password) {
    $auth = $this->supabaseService->createAuth();
    try {
      $auth->signInWithEmailAndPassword($email, $password);
      return $auth->data();
    } catch (\Exception $e) {
      return $auth->getError();
    }
  }
}
