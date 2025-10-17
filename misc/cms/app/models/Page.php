<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';
class Page{
    private $db;
    public $layout;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getPage($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM about WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return  $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getPages(){
        $stmt = $this->db->conn->prepare("SELECT * FROM page");
        $stmt->execute();
        return  $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getBySlug($slug){
        $stmt = $this->db->conn->prepare("SELECT * FROM page WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return  $stmt->fetch(\PDO::FETCH_OBJ);
    }
    public function create(array $data){
    $stmt = $this->db->conn->prepare("INSERT INTO page(title, slug, content, layout) VALUES(:title, :slug, :content, :layout)");
    return $stmt->execute([':title' => isset($data['title']) ? $data['title'] : null, ':slug' => isset($data['slug']) ? $data['slug'] : null, ':content' => isset($data['content']) ? $data['content'] : null, ':layout' => isset($data['layout']) ? $data['layout'] : null]);
    }

    public function update($data, $id){
        $stmt = $this->db->conn->prepare("UPDATE page SET title = :title, slug = :slug, content = :content WHERE id = :id");
        $success = $stmt->execute([':title' => $data['title'], ':slug' => $data['slug'], ':content' => $data['content'], ':id' => $id]);
        if(!$success){
            die('Update failed');
        }
    }

    public function delete($id){
        $stmt = $this->db->conn->prepare("DELETE FROM page WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
