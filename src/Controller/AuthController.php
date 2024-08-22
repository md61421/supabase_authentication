<?php

namespace Drupal\supabase_authentication\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Controller routines for login and registration REST routes.
 */
class AuthController extends ControllerBase {

  /**
   * Register a user.
   */
  public function register(Request $request) {
    $data = json_decode($request->getContent(), TRUE);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
      throw new HttpException(400, 'Email and password are required.');
    }

    // Assuming username is same as email.
    $user = User::create([
      'name' => $email,
      'mail' => $email,
      'pass' => $password,
      'status' => 1,
      // Or 0 if you require email verification.
    ]);

    // Add validation or additional fields as needed.
    $user->save();

    // Optionally integrate with Supabase.
    $supabase_service = \Drupal::service('supabase_authentication.supabase');
    $supabase_response = $supabase_service->createUser($email, $password);

    // Check response and handle errors.
    return new JsonResponse([
      'message' => 'User registered successfully.',
      'supabase_id' => $supabase_response->id ?? '',
    ]);
  }

  /**
   * Log in a user.
   */
  public function login(Request $request) {
    $data = json_decode($request->getContent(), TRUE);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
      throw new HttpException(400, 'Username and password are required.');
    }

    if (\Drupal::service('user.auth')->authenticate($username, $password)) {
      // Generate session token or similar mechanism.
      return new JsonResponse(['message' => 'User logged in successfully.']);
    }
    else {
      throw new HttpException(401, 'Invalid credentials.');
    }
  }

}
