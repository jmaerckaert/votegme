<?php


namespace Drupal\unique_token;

use Drupal\Core\Database\Connection;

/**
 * Class TokenManager
 *
 * This class manages the generation, validation, and updating of unique tokens
 * within the 'unique_tokens' database table.
 */
class TokenManager {
  // Database connection object.
  protected Connection $database;

  /**
   * TokenManager constructor.
   *
   * This constructor injects the database connection, allowing the class
   * to interact with the Drupal database API.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection service injected into the class.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Generates a unique token and stores it in the database.
   *
   * This method generates a random 32-character token using `random_bytes()`,
   * then inserts the token into the 'unique_tokens' table with an initial
   * 'status' of 0 (unused).
   *
   * @return string
   *   The generated token string.
   */
  public function generateToken(string $webform_id): string {
    // Generate a 32-character unique token using random bytes.
    $token = bin2hex(random_bytes(16));

    // Insert the token into the database with an initial 'status' of 0.
    $this->database->insert('tokens')
      ->fields(['token' => $token, 'status' => 0, 'webform_id' => $webform_id])
      ->execute();

    // Return the generated token.
    return $token;
  }

  /**
   * Validates and marks the token as used if valid.
   *
   * This method checks the status of a given token in the 'unique_tokens' table.
   * If the token doesn't exist, it returns 'invalid'. If the token has already
   * been used (status = 1), it returns 'used'. If the token is valid (status = 0),
   * it updates the token's status to 1 (used) and returns 'valid'.
   *
   * @param string $token
   *   The token to validate.
   *
   * @return string
   *   Returns 'invalid' if the token does not exist, 'used' if the token has
   *   already been used, and 'valid' if the token is valid and updated.
   */
  public function validateToken(string $token): string {
    // Query the database to check the status of the provided token.
    $query = $this->database->select('unique_tokens', 'ut')
      ->fields('ut', ['status'])
      ->condition('token', $token)
      ->execute()
      ->fetchField();

    // If no token is found, return 'invalid'.
    if ($query === FALSE) {
      return 'invalid';
    }
    // If the token has already been used (status = 1), return 'used'.
    elseif ($query == 1) {
      return 'used';
    }
    // If the token is valid (status = 0), mark it as used (status = 1) and return 'valid'.
    else {
      $this->database->update('unique_tokens')
        ->fields(['status' => 1])
        ->condition('token', $token)
        ->execute();
      return 'valid';
    }
  }
}

