<?php

namespace app\models;

use Database;

class Standard extends Features{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

}
