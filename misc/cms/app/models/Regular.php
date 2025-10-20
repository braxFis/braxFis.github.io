<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Regular{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }


}
