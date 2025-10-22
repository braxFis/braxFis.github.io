<?php

namespace app\models;

class Share{

    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getShare($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM share WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getShares(){
        $stmt = $this->db->conn->prepare('SELECT * from share');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function create($data){
        $stmt = $this->db->conn->prepare("INSERT INTO share(name, description, link, post_id) VALUES(:name, :description, :link, :post_id)");
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'link' => $data['link'],
            'post_id' => $data['post_id']            
        ]);
    }

    public function update($data, $id){
        $stmt = $this->db->conn->prepare('UPDATE share SET name = :name, description = :description, link = :link WHERE id = :id');
        $stmt->execute([
            'name'=> $data['name'],
            'description'=> $data['description'],
            'link'=> $data['link'],
            'id' => $id
        ]);
    }

    public function delete($id){
        $stmt = $this->db->conn->prepare('DELETE FROM share WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
    
}