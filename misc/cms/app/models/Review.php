<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Review{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function getReview($id){
    $stmt = $this->db->conn->prepare("SELECT * FROM reviews WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_OBJ);
  }

  public function getReviews(){
    $stmt = $this->db->conn->prepare("SELECT * FROM reviews");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function create($data){
    $stmt = $this->db->conn->prepare("INSERT INTO reviews(title, subtitle, content, date, author, category, genre, media, platform, status, tags, rating, score_id) VALUES (:title, :subtitle, :content, :date, :author, :category, :genre, :media, :platform, :status, :tags, :rating, :score_id)");
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
      'rating' => $data['rating'],
      'score_id' => $data['score_id']
      ]);
  }

  public function update($data, $id){
    $stmt = $this->db->conn->prepare("UPDATE reviews SET title = :title, subtitle = :subtitle, content = :content, date = :date, author = :author, category = :category, genre = :genre, media = :media, platform = :platform, status = :status, tags = :tags, rating = :rating, score_id = :score_id WHERE id = :id");
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
      'rating' => $data['rating'],
      'score_id' => $data['score_id'],
      'id' => $id
      ]);
  }

  public function delete($id){
    $stmt = $this->db->conn->prepare("DELETE FROM reviews WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
