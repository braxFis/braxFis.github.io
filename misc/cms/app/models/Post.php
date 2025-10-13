<?php

namespace  app\models;

require_once __DIR__ . '/../../bootstrap.php';
class Post{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function createPost($title, $content, $category_id, $imageFileName = null){

        $sql = "INSERT INTO posts (title, content, category_id, user_id, image) VALUES(:title, :content, :category_id, :user_id, :image)";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':image', $imageFileName);
        $stmt->execute();
    }

    public function getPost($id){
        $sql = "SELECT posts.*, categories.name AS category_name, posts.image AS image 
        FROM posts 
        LEFT JOIN categories ON posts.category_id = categories.id 
        WHERE posts.id = :id";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getPosts($categoryId = null) {
        if ($categoryId) {
            $sql = "SELECT posts.*, categories.name AS category_name, posts.image as image
                FROM posts 
                LEFT JOIN categories ON posts.category_id = categories.id 
                WHERE posts.category_id = :categoryId";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute(['categoryId' => $categoryId]);
        } else {
            $sql = "SELECT posts.*, categories.name AS category_name 
                FROM posts 
                LEFT JOIN categories ON posts.category_id = categories.id 
                ORDER BY posts.id DESC";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function searchPosts($query){
        $query = '%'.$query.'%';
        $stmt = $this->db->conn->prepare("SELECT posts.*, categories.name as category, posts.image as image from posts LEFT JOIN categories ON posts.category_id = categories.id WHERE posts.title LIKE :query or posts.content like :query");
        $stmt->bindValue(':query', $query, \PDO::PARAM_STR);
        $stmt->execute();
        return  $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    public function update($id, $title, $content, $category_id, $imageFileName = null)
    {
        $sql = "UPDATE posts SET title = :title, content = :content, category_id = :category_id, image = :image WHERE id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':category_id' => $category_id,
            ':image' => $imageFileName,
            ':id' => $id
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->conn->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC); // or FETCH_OBJ if you prefer objects
    }
    public function deletePost($id){
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function create($data)
    {
        $stmt = $this->db->conn->prepare("
            INSERT INTO posts (title, content, category_id, image)
            VALUES (:title, :content, :category_id, :image)
        ");

        $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'category_id' => $data['category_id'],
            'image' => $data['image']
        ]);
    }

}