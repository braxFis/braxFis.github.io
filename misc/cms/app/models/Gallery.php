<?php

namespace app\models;

class Gallery{
    private $db;
    
    public function __construct(){
        $this->db = new \Database;
    }

    public function getGallery(){
        $stmt = $this->db->conn->prepare("SELECT * FROM news");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}