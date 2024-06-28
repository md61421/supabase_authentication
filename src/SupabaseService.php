<?php

namespace Drupal\supabase_authentication;

use Drupal\Core\Config\ConfigFactoryInterface;
use PHPSupabase\Service;

/**
 * Contains \Drupal\supabase_authentication\SupabaseService.
 */

/**
 * Service class for integrating with Supabase.
 */
class SupabaseService {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The Supabase service.
   *
   * @var \PHPSupabase\Service
   */
  protected $supabaseService;

  /**
   * SupabaseService constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    $config = $this->configFactory->get('supabase_authentication.config_form');

    $apiKey = $config->get('api_key');
    $url = $config->get('url');
    $this->supabaseService = new Service($apiKey, $url);
  }

  /**
   * Creates a new user.
   *
   * @param string $email
   *   The email of the user.
   * @param string $password
   *   The password of the user.
   * @param array $metadata
   *   (optional) Additional metadata for the user.
   *
   * @return array
   *   The data of the created user or an error.
   */
  public function createUser($email, $password, array $metadata = []) {
    $auth = $this->supabaseService->createAuth();
    try {
      $auth->createUserWithEmailAndPassword($email, $password, $metadata);
      return $auth->data();
    }
    catch (\Exception $e) {
      return $auth->getError();
    }
  }

  /**
   * Signs in a user.
   *
   * @param string $email
   *   The email of the user.
   * @param string $password
   *   The password of the user.
   *
   * @return array
   *   The data of the signed-in user or an error.
   */
  public function signInUser($email, $password) {
    $auth = $this->supabaseService->createAuth();
    try {
      $auth->signInWithEmailAndPassword($email, $password);
      return $auth->data();
    }
    catch (\Exception $e) {
      return $auth->getError();
    }
  }

}

