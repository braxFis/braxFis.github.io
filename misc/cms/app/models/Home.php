<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Home{
    private $db;
    public function __construct(){
        $this->db = new \Database;
    }
}