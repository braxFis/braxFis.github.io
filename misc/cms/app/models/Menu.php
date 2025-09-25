<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';
class Menu{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getMenuItem($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM menu WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getMenuItems(){
        $stmt = $this->db->conn->prepare("SELECT * FROM menu");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function create($data)
    {
        $sql = "INSERT INTO menu (label, url, sort_order) VALUES (:label, :url, :sort_order)";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(['label' => $data['label'], 'url' => $data['url'], 'sort_order' => $data['sort_order']]);
    }

    public function updateMenu($id, $data){
        $sql = "UPDATE menu SET label = :label, sort_order = :sort_order WHERE id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':label' => $data['label'], ':url' => $data['url'], ':sort_order' => $data['sort_order'], ':id' => $id]);
    }

    public function deleteMenu($id){
        $sql = "DELETE FROM menu WHERE id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}