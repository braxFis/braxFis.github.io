<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';
class Footer{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getFooterItem($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM footer WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return  $stmt->fetch(\PDO::FETCH_OBJ);
    }
    public function getFooterItems(){
        $stmt = $this->db->conn->prepare("SELECT * FROM footer");
        $stmt->execute();
        return  $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function create($data){
        $sql = "INSERT INTO footer (label, url, sort_order) VALUES (:label, :url, :sort_order)";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':label' => $data['label'], ':url' => $data['url'], ':sort_order' => $data['sort_order']]);
    }

    public function updateFooter($id, $data){
        $sql = "UPDATE footer SET label = :label, url = :url, sort_order = :sort_order WHERE id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':label'=> isset($data['label']) ? $data['label'] : null, ':url'=> isset($data['url']) ? $data['url'] : null, ':sort_order' => isset($data['sort_order']) ? $data['sort_order'] : null, ':id'=>$id]);
    }

    public function deleteFooter($id){
        $sql = "DELETE FROM footer WHERE id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}