<?php
namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';
class DragDrop {
    public $db;

    public function __construct() {
        $this->db = new \Database;
    }

    public function saveLayout($slug, $layout) {
        error_log("💾 saveLayout() körs för slug={$slug}");
        error_log("💾 Layout-data: " . print_r($layout, true));
        $stmt = $this->db->conn->prepare(
            "UPDATE page SET layout = :layout WHERE slug = :slug"
        );
     
        $result = $stmt->execute([
            ':layout' => json_encode($layout, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            ':slug' => $slug
        ]);
        error_log("💾 Result: " . ($result ? 'OK' : 'FAIL'));
        return $result;
    }

    public function getLayout($title) {
        $stmt = $this->db->conn->prepare("SELECT layout FROM page WHERE title = :title");
        $stmt->execute([':title' => $title]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['layout'] ?? '[]';
    }
}
