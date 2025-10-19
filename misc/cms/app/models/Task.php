<?php
namespace app\models;

use PDO;

class Task {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function all() {
        $stmt = $this->db->prepare("SELECT * FROM tasks ORDER BY due_date ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($title, $description, $due_date) {
        $stmt = $this->db->prepare(
            "INSERT INTO tasks (title, description, due_date) VALUES (:title, :description, :due_date)"
        );
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':due_date' => $due_date
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function get($id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $description, $due_date) {
        $stmt = $this->db->prepare(
            "UPDATE tasks SET title = :title, description = :description, due_date = :due_date WHERE id = :id"
        );
        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':description' => $description,
            ':due_date' => $due_date
        ]);
    }
}
