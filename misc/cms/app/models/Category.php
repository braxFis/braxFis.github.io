<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';
class Category{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getCategory($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getCategories(){
        $stmt = $this->db->conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function create($data){
        $stmt =  $this->db->conn->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
        $stmt->execute(['name' => $data['name'], 'description' => $data['description']]);
    }

    public function update($data, $id){
        $stmt = $this->db->conn->prepare("UPDATE categories SET name = :name, description = :description WHERE id = :id");
        $stmt->execute(['name' => $data['name'], 'description' => $data['description'], 'id' => $id]);
    }

    public function delete($id){
        $stmt = $this->db->conn->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getPosts($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}