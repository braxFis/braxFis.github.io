<?php

namespace app\models\newsletter;

require_once __DIR__ . '/../../bootstrap.php';
class Subscriber{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function addSubscriber($data){
        $stmt = $this->db->conn->prepare("INSERT INTO subscribers (email, name, subscribed_at) VALUES (:email, :name, :subscribed_at)");
        $stmt->execute(['email' => $data['email'], 'name' => $data['name'], 'subscribed_at' => $data['subscribed_at']]);
    }

    public function removeSubscriber($id){
        $stmt = $this->db->conn->prepare("DELETE FROM subscribers WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getSubscribers(): array
    {
        $stmt = $this->db->conn->prepare("SELECT * FROM subscribers");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getSubscriber($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM subscribers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function updateSubscriber($data, $id){
        //$stmt = $this->db->conn->prepare("UPDATE subscribers SET ")
    }
}