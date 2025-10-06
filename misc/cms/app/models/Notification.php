<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Notification{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function getNotification(){
    $stmt = $this->db->conn->prepare("SELECT * FROM reviews AS REV UNION previews AS PREV UNION news AS NEWS WHERE REV.date=CURRENT TIMESTAMP OR PREV.date=CURRENT TIMESTAMP OR NEWS.date=CURRENT TIMESTAMP");
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_OBJ);
  }

  public function getNotifications(){}
}
