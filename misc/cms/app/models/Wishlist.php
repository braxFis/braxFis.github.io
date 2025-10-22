<?php 

namespace app\models;

class Wishlist{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

        public function getWishlist($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM wishlist WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getWishlists(){
        $stmt = $this->db->conn->prepare('SELECT * from wishlist');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function create($data){
        $stmt = $this->db->conn->prepare("INSERT INTO wishlist(name, description, type) VALUES(:name, :description, :type)");
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'type' => $data['type'],
        ]);
    }

    public function update($data, $id){
        $stmt = $this->db->conn->prepare('UPDATE wishlist SET name = :name, description = :description, type = :type WHERE id = :id');
        $stmt->execute([
            'name'=> $data['name'],
            'description'=> $data['description'],
            'type'=> $data['type'],
            'id' => $id
        ]);
    }

    public function delete($id){
        $stmt = $this->db->conn->prepare('DELETE FROM wishlist WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}