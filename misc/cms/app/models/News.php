<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class News{
  private $db;

  public function __construct(){
    $this->db = new \Database;
  }

  public function createNews($title, $subtitle, $content, $date, $author){
    $sql = "INSERT INTO news(title, subtitle, content, date, author) VALUES (:title, :subtitle, :content, :date, :author)";
    $stmt = $this->db->conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':subtitle', $subtitle);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':author', $author);
    $stmt->execute();
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
    $stmt = $this->db->conn->prepare("INSERT INTO news(title, subtitle, content, date, author) VALUES (:title, :subtitle, :content, :date, :author)");
    $stmt->execute([
      'title' => $data['title'],
      'subtitle' => $data['subtitle'],
      'content' => $data['content'],
      'date' => $data['date'],
      'author' => $data['author']
    ]);
  }

  public function update($data, $id){
    $stmt = $this->db->conn->prepare("UPDATE news SET title = :title, subtitle = :subtitle, content = :content, date = :date, author = :author WHERE id = :id");
    $stmt->execute([
      'title' => $data['title'],
      'subtitle' => $data['subtitle'],
      'content' => $data['content'],
      'date' => $data['date'],
      'author' => $data['author'],
      'id' => $id
    ]);
  }

  public function delete($id){
    $stmt = $this->db->conn->prepare("DELETE FROM news WHERE id = :id");
    $stmt->execute(['id' => $id]);
  }
}
