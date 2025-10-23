<?php
namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Read
{
    private $db;

    public function __construct()
    {
        $this->db = new \Database(); // se till att denna är korrekt
    }

    public function add(int $user_id, int $article_id): bool
    {
        if (!$user_id || !$article_id) return false;

        $stmt = $this->db->conn->prepare("
            INSERT IGNORE INTO readlist (user_id, article_id, created_at)
            VALUES (?, ?, NOW())
        ");
        return $stmt->execute([$user_id, $article_id]);
    }

    public function getReadList(int $user_id): array
    {
        $stmt = $this->db->conn->prepare("
            SELECT n.id, n.title, n.content, n.date, r.created_at
            FROM readlist r
            JOIN news n ON r.article_id = n.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // valfri: rensa föräldralösa poster
    public function cleanupOrphans(): int
    {
        return $this->db->conn->exec("
            DELETE FROM readlist
            WHERE article_id NOT IN (SELECT id FROM news)
        ");
    }
}
