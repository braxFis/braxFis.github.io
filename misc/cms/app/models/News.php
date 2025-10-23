<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class News{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function getNew($id){
    $stmt = $this->db->conn->prepare("SELECT * FROM news WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_OBJ);
  }

  public function getNews(){
    $stmt = $this->db->conn->prepare("SELECT * FROM news");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function create($data){
    $stmt = $this->db->conn->prepare("INSERT INTO news(title, subtitle, content, date, author, media, tags, layout, user_id) VALUES (:title, :subtitle, :content, :date, :author, :media, :tags, :layout, :user_id)");
    $stmt->execute([
      'title' => $data['title'],
      'subtitle' => $data['subtitle'],
      'content' => $data['content'],
      'date' => $data['date'],
      'author' => $data['author'],
      'media' => $data['media'],
      'tags' => $data['tags'],
      'layout' => json_encode($data['layout'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
      'user_id' => $data['user_id'],
    ]);
  }

  public function update($data, $id){
    $stmt = $this->db->conn->prepare("UPDATE news SET title = :title, subtitle = :subtitle, content = :content, date = :date, author = :author, media = :media, tags = :tags, layout = :layout, user_id = :user_id WHERE id = :id");
    $stmt->execute([
      'title' => $data['title'],
      'subtitle' => $data['subtitle'],
      'content' => $data['content'],
      'date' => $data['date'],
      'author' => $data['author'],
      'media' => $data['media'],
      'tags' => $data['tags'],
      'layout' => json_encode($data['layout'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
      'user_id'=> $data['user_id'],
      'id' => $id
    ]);
  }

  public function delete($id){
    $stmt = $this->db->conn->prepare("DELETE FROM news WHERE id = :id");
    $stmt->execute(['id' => $id]);
  }
}
