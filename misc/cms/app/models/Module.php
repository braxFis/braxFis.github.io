<?php
namespace app\models;

use Database;

class Module {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  public function moveModule($id, $newController, $newPosition) {
    $stmt =  $this->db->conn->prepare('UPDATE modules SET controller=:controller, position=:position WHERE id=:id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':position', $newPosition);
    $stmt->execute();
  }
}
