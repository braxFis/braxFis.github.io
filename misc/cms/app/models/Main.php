<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';;

class Main{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }


}