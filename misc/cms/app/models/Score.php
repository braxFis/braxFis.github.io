<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Score{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getScore($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM scores");
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data){
        $stmt = $this->db->conn->prepare('INSERT INTO scores(scorecard) VALUES(:scorecard)');
        $stmt->execute([
            'scorecard' => json_encode($data['scorecard']),
        ]);
    }

    public function update($id, $data){
    $stmt = $this->db->conn->prepare('UPDATE scores SET scorecard = :scorecard');
        $stmt->execute([
            'scorecard' => json_encode($data['scorecard']),
            'id' => $id
        ]);
    }

    public function delete($id){
        $stmt = $this->db->conn->prepare('DELETE FROM scores WHERE id = :id');
        $stmt->execute(['id'=> $id]);
    }
}