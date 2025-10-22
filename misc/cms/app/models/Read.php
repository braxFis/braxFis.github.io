<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Read{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function read($user_id, $article_id){
        if(!$user_id || !$article_id){
            exit('Ej inloggad eller ogiltigt id');
        }
        $stmt = $this->db->conn->prepare('INSERT IGNORE INTO readlist(user_id, article_id) VALUES(?, ?)');
        $stmt->execute([$user_id, $article_id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
        //header('Location: /read_list');
        //exit;
    }

    public function remove($user_id, $article_id){
        $stmt = $this->db->conn->prepare('DELETE FROM readlist WHERE id = ? AND article_id = ?');
        $stmt->execute([$user_id, $article_id]);
    }

    public function getReadList($user_id){
        $stmt = $this->db->conn->prepare("
            SELECT n.id, n.title, n.content, n.date
            FROM readlist r
            JOIN news n ON r.article_id = n.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

}