<?php

namespace app\models;

use Database;

class Media{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getMedia($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM media WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getMedias(){
        $stmt = $this->db->conn->prepare("SELECT * FROM media");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function create($data){
        $sql = "INSERT INTO media (title, media_type, url, related_type, related_id, uploaded_by, uploaded_at, image_url) VALUES(:title, :media_type, :url, :related_type, :related_id, :uploaded_by, :uploaded_at, :image_url)";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':media_type' => $data['media_type'],
            ':url' => $data['url'],
            ':related_type' => $data['related_type'],
            ':related_id' => $data['related_id'],
            ':uploaded_by' => $data['uploaded_by'],
            ':uploaded_at' => $data['uploaded_at'],
            ':image_url' => $data['image_url']
        ]);
    }

    public function updateMedia($id, $data){
        $sql = "UPDATE media SET title = :title, media_type = :media_type, url = :url, related_type = :related_type, related_id = :related_id, uploaded_by = :uploaded_by, uploaded_at = :uploaded_at WHERE id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':media_type' => $data['media_type'],
            ':url' => $data['url'],
            ':related_type' => $data['related_type'],
            ':related_id' => $data['related_id'],
            ':uploaded_by' => $data['uploaded_by'],
            ':uploaded_at' => $data['uploaded_at']
        ]);
    }

    public function deleteMedia($id){
        $sql = "DELETE FROM media WHERE id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
    }

    public function getMediaTypes1() {
        $stmt = $this->db->conn->prepare("SHOW COLUMNS FROM media LIKE 'media_type'");
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row || !isset($row['Type'])) {
            return []; // or throw an exception if this is critical
        }
        // This gives: enum('image','video','audio','document')
        preg_match_all("/'([^']+)'/", $row['Type'], $matches);
        return $matches[1]; // → ['image', 'video', 'audio', 'document']
    }

    public function getMediaTypes2(){
        $stmt = $this->db->conn->prepare("SHOW COLUMNS FROM media LIKE 'related_type'");
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row || !isset($row['Type'])) {
            return [];
        }
        preg_match_all("/'([^']+)'/", $row['Type'], $matches);
        return $matches[1];
    }
}