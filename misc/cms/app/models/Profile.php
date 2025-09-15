<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';
class Profile{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function view($id){
    $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function edit(){
    $stmt = $this->db->conn->prepare("SELECT * FROM users");
    $stmt->execute();
  }

  public function update($id, $data) {
    $fields = ['username' => $data['username'], 'email' => $data['email']];
    $sql = "UPDATE users SET username = :username, email = :email";

    if (!empty($data['password'])) {
      $fields['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
      $sql .= ", password = :password";
    }

    if (!empty($data['image'])) {
      $fields['image'] = $data['image'];
      $sql .= ", image = :image";
    }

    $sql .= " WHERE id = :id";
    $fields['id'] = $id;

    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute($fields);
  }


  public function delete($id){
    $stmt = $this->db->conn->prepare("DELETE FROM users WHERE id = :id");
    return $stmt->execute([':id' => $id]);
  }
}
