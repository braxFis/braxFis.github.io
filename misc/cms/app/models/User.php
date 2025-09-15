<?php

namespace  app\models;

use PDOException;

require_once __DIR__ . '/../../bootstrap.php';
class User{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function login($data)
  {
    $username = isset($data['username']) ? trim($data['username']) : '';
    $password = isset($data['password']) ? $data['password'] : '';

    try {
      $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE username = :username");
      $stmt->execute([':username' => $username]);
      $user = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!$user) {
        return ['status' => 'error', 'message' => 'User not found'];
      }

      if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        return [
          'status' => 'success',
          'user_id' => $user['id'],
          'username' => $user['username'],
          'role' => $user['role']
        ];
      } else {
        return ['status' => 'error', 'message' => 'Incorrect password'];
      }

    } catch (PDOException $e) {
      return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
  }


  public function logout($data){

  }

  public function register($data){
    if (!isset($data['username'], $data['email'], $data['password'], $data['role'])) {
      return ['status' => 'error', 'message' => 'Missing input fields'];
    }

    $hash = password_hash($data['password'], PASSWORD_DEFAULT);

    $role = strtolower(trim($data['role']));
    $allowedRoles = ['admin', 'user'];

    if (!in_array($role, $allowedRoles)) {
      return ['status' => 'error', 'message' => 'Invalid role'];
    }

    try {
      $stmt = $this->db->conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password, :role)");
      $stmt->execute([
        ':username' => $data['username'],
        ':email' => $data['email'],
        ':password' => $hash,
        ':role' => $role
      ]);
      return ['status' => 'success'];
    } catch (PDOException $e) {
      if ($e->getCode() == 23000) {
        return ['status' => 'error', 'message' => 'Email already exists'];
      }
      return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
  }


}
