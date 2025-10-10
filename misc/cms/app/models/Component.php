<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Component {

  private $db;

  public function __construct(){
    $this->db = new \Database;
  }
  public function move($componentId, $newController) {
    $stmt = $this->db->conn->prepare("UPDATE components SET controller = :controller WHERE id = :id");
    $stmt->execute(['controller' => $newController, 'id' => $componentId]);
  }

  public function allByController($controller): array
  {
    $stmt = $this->db->conn->prepare("SELECT * FROM components WHERE controller = :controller");
    $stmt->execute(['controller' => $controller]);
    return $stmt->fetchAll();
  }
}
