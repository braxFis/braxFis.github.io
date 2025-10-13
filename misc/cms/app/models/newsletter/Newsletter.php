<?php

namespace app\models\newsletter;

require_once __DIR__ . '/../../bootstrap.php';
class Newsletter{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getOne($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM newsletter WHERE id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    public function getAll(): array
    {
        $stmt =  $this->db->conn->prepare("SELECT * FROM newsletter");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function create($data){
        $stmt = $this->db->conn->prepare("INSERT INTO newsletter (title, body) VALUES (:title, :body)");
        $stmt->execute(['title'=>$data['title'], 'body'=>$data['body']]);
    }

    public function updateNewsletter($data, $id){
        $stmt =  $this->db->conn->prepare("UPDATE newsletter SET title=:title, body=:body WHERE id=:id");
        $stmt->execute(['title'=>$data['title'], 'body'=>$data['body'], 'id'=>$id]);
    }

    public function deleteNewsletter($id){
        $stmt = $this->db->conn->prepare("DELETE FROM newsletter WHERE id=:id");
        $stmt->execute(['id'=>$id]);
    }

    public function markAsSent($id): bool
    {
        $stmt =  $this->db->conn->prepare("UPDATE newsletter SET status='sent' WHERE id=:id");
        return $stmt->execute(['id'=>$id]);
    }
}