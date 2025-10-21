<?php

namespace app\models;

class Premium extends Features{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function interviews(){}
}
