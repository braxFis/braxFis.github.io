<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Dashboard{
  private $db;

  public function __construct()
  {
    $this->db = new \Database;
  }

  //Decide what to include
}

?>
