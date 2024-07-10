<?php

namespace Drupal\supabase_authentication;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use PHPSupabase\Service;
use Psr\Log\LoggerInterface;

/**
 * Provides methods to interact with the Supabase authentication service.
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
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The logger channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * SupabaseService constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger channel.
   */
  public function __construct(ConfigFactoryInterface $config_factory, Connection $database, LoggerInterface $logger) {
    $this->configFactory = $config_factory;
    $this->database = $database;
    $this->logger = $logger;
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

  /**
   * Stores user credentials in the custom table.
   *
   * @param string $email
   *   The email of the user.
   * @param string $password
   *   The password of the user.
   */
  public function storeUserCredentials($email, $password) {
    $this->database->insert('supabase_users_field_table')
      ->fields([
        'email' => $email,
        'password' => $password,
        'created' => \Drupal::time()->getRequestTime(),
      ])
      ->execute();
  }

  /**
   * Logs a message.
   *
   * @param string $level
   *   The log level (e.g., 'notice', 'error').
   * @param string $message
   *   The log message.
   * @param array $context
   *   Additional context for the log message.
   */
  public function log($level, $message, array $context = []) {
    $this->logger->$level($message, $context);
  }

  /**
   * Drops the custom table.
   */
  public function dropTable() {
    $schema = $this->database->schema();
    if ($schema->tableExists('supabase_users_field_table')) {
      $schema->dropTable('supabase_users_field_table');
    }
  }

}
