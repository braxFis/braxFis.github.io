<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Preview{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function getPreview($id){
    $stmt = $this->db->conn->prepare("SELECT * FROM previews WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_OBJ);
  }

  public function getPreviews(){
    $stmt = $this->db->conn->prepare("SELECT * FROM previews");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function getTop100Previews(){
    $stmt = $this->db->conn->prepare("SELECT * FROM previews WHERE rating > 70 LIMIT 100 ORDER BY ASC");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }
  public function create($data){
    $stmt = $this->db->conn->prepare("INSERT INTO previews(title, subtitle, content, date, author, category, genre, media, platform, status, tags, release_date) VALUES (:title, :subtitle, :content, :date, :author, :category, :genre, :media, :platform, :status, :tags, :release_date)");
    $stmt->execute([
      ':title' => $data['title'],
      'subtitle' => $data['subtitle'],
      'content' => $data['content'],
      'date' => $data['date'],
      'author' => $data['author'],
      'category' => isset($data['category']) ? $data['category'] : null,
      'genre' => $data['genre'],
      'media' => $data['media'],
      'platform' => $data['platform'],
      'status' => $data['status'],
      'tags' => $data['tags'],
      'release_date' => $data['release_date']
      ]);
  }

  public function update($data, $id){
    $stmt = $this->db->conn->prepare("UPDATE previews SET title = :title, subtitle = :subtitle, content = :content, date = :date, author = :author, category = :category, genre = :genre, media = :media, platform = :platform, status = :status, tags = :tags, release_date = :release_date WHERE id = :id");
    $stmt->execute([
      'title' => $data['title'],
      'subtitle' => $data['subtitle'],
      'content' => $data['content'],
      'date' => $data['date'],
      'author' => $data['author'],
      'category' => $data['category'],
      'genre' => $data['genre'],
      'media' => $data['media'],
      'platform' => $data['platform'],
      'status' => $data['status'],
      'tags' => $data['tags'],
      'release_date' => $data['release_date'],
      'id' => $id
      ]);
  }

  public function delete($id){
    $stmt = $this->db->conn->prepare("DELETE FROM previews WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
