<?php

namespace app\models;

use app\templates\Model;

class Todo implements Model{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getTodo($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM todo where id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getTodos(){
        $stmt = $this->db->conn->prepare('SELECT * FROM todo');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function create($data = null){
        $stmt = $this->db->conn->prepare('INSERT INTO todo(id, name, description, type) VALUES(:id, :name, :description, :type)');
        $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'type' => $data['type']
        ]);
    }

    public function edit($id){}

    public function update($id, $data){
        $stmt = $this->db->conn->prepare('UPDATE todo SET name = :name, description = :description, type = :type WHERE id = :id');
        $stmt->execute([
            'id'=> $data['id'],
            'name'=> $data['name'],
            'description'=> $data['description'],
            'type'=> $data['type']
            ]);
    }

    public function delete($id){
        $stmt = $this->db->conn->prepare('DELETE FROM todo WHERE id = :id');
        $stmt->execute(['id'=> $id]);
    }

}