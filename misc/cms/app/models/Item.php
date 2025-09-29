<?php
namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Item {
  private $db;

  public function __construct() {
    $this->db = new \Database; // however your DB is set up
  }

  public function getItems(): array
  {
    $stmt = $this->db->conn->prepare("SELECT * FROM media");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }
  public function updatePosition($id, $position) {
    $stmt = $this->db->conn->prepare("UPDATE media SET position = :position WHERE id = :id");
    $stmt->execute([
      ":position" => $position,
      ":id" => $id
    ]);
  }
}
