<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Preview{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function createPreview($title, $subtitle, $content, $author, $category, $genre, $platform, $status, $tags){
    $sql = "INSERT INTO reviews(title, subtitle, content, author, category, genre, media, platform, status, tags) VALUES(:title, :subtitle, :content, :author, :category, :genre, :platform, :status, :tags)";
    $stmt = $this->db->conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':subtitle', $subtitle);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':genre', $genre);
    $stmt->bindParam(':media', $media);
    $stmt->bindParam(':platform', $platform);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':tags', $tags);
    $stmt->execute();
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

  public function create($data){
    $stmt = $this->db->conn->prepare("INSERT INTO previews(title, subtitle, content, author, category, genre, media, platform, status, tags) VALUES (:title, :subtitle, :content, :author, :category, :genre, :media, :platform, :status, :tags)");
    $stmt->execute([
      ':title' => $data['title'],
      'subtitle' => $data['subtitle'],
      'content' => $data['content'],
      'author' => $data['author'],
      'category' => isset($data['category']) ? $data['category'] : null,
      'genre' => $data['genre'],
      'media' => $data['media'],
      'platform' => $data['platform'],
      'status' => $data['status'],
      'tags' => $data['tags']
      ]);
  }

  public function update($data, $id){
    $stmt = $this->db->conn->prepare("UPDATE previews SET title = :title, subtitle = :subtitle, content = :content, author = :author, category = :category, genre = :genre, media = :media, platform = :platform, status = :status, tags = :tags WHERE id = :id");
    $stmt->execute([
      'title' => $data['title'],
      'subtitle' => $data['subtitle'],
      'content' => $data['content'],
      'author' => $data['author'],
      'category' => $data['category'],
      'genre' => $data['genre'],
      'media' => $data['media'],
      'platform' => $data['platform'],
      'status' => $data['status'],
      'tags' => $data['tags'],
      'id' => $id
      ]);
  }

  public function delete($id){
    $stmt = $this->db->conn->prepare("DELETE FROM previews WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
