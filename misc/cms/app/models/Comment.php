<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';
class Comment
{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function createComment($postId, $body, $userId)
    {
        $stmt = $this->db->conn->prepare("
        INSERT INTO comments (post_id, body, user_id)
        VALUES (:post_id, :body, :user_id)
    ");

        $stmt->execute([
            'post_id' => $postId,
            'body' => $body,
            'user_id' => $userId
        ]);
    }


    public function getComments($postId): array
    {
        $stmt = $this->db->conn->prepare("SELECT * FROM comments WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getComment($postId){
        $stmt = $this->db->conn->prepare("SELECT * FROM comments WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    public function updateComment($id, $data): bool
    {
        $stmt = $this->db->conn->prepare("UPDATE comments SET body = ? WHERE id = ?");
        return $stmt->execute([$id, $data['content']]);
    }

    public function deleteComment($id){
        $comment = $this->getComment($id);
        if($comment){
            $stmt = $this->db->conn->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
}