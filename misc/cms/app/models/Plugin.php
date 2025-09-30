<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Plugin{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function getPlugins(): array
  {
    $stmt = $this->db->conn->prepare("SELECT * FROM plugins");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function getPlugin($id){
    $stmt = $this->db->conn->prepare("SELECT * FROM plugins WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_OBJ);
  }

  public function install($data){
    $stmt = $this->db->conn->prepare("INSERT INTO plugins(name, active) VALUES(:name, :active)");
    $stmt->execute(['name' => $data['name'], 'active' => $data['active']]);
  }
  public function update($data, $id){
    $stmt = $this->db->conn->prepare("UPDATE plugins SET name = :name WHERE id = :id");
    $success = $stmt->execute(['name' => $data['name'], 'id' => $id]);
    if(!$success){
      die('Update failed');
    }
  }

  public function delete($id){
    $stmt = $this->db->conn->prepare("DELETE FROM plugins WHERE id = :id");
    $stmt->execute(['id' => $id]);
  }
}
