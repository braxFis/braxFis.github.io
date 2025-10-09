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
    $stmt = $this->db->conn->prepare("
SELECT * FROM (
        SELECT title,date FROM reviews
        UNION
        SELECT title,date FROM previews
        UNION
        SELECT title,date FROM news
    ) AS combined
    WHERE date >= NOW() - INTERVAL 1 HOUR
");
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_OBJ);
  }

  public function getNotifications(){}
}
