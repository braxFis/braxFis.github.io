<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Score{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    public function getScore($id){
        $stmt = $this->db->conn->prepare("SELECT * FROM scores");
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data, $review_id){
        $stmt = $this->db->conn->prepare('INSERT INTO scores(scorecard, review_id) VALUES(:scorecard, :review_id)');
        $stmt->execute([
            'scorecard' => json_encode($data['scorecard']),
            'review_id' => $data['review_id']
        ]);
        // Uppdatera rating direkt efter insättning
        $this->updateReviewRating($review_id);
    }

 // Summera scorecard och uppdatera reviews.rating
    protected function updateReviewRating($review_id) {
        $sql = "
            UPDATE reviews r
            JOIN scores s ON s.review_id = r.id
            SET r.rating = (
                SELECT SUM(CAST(j.value AS UNSIGNED))
                FROM JSON_TABLE(
                    s.scorecard, '$.scorecard.*.*'
                    COLUMNS(value VARCHAR(10) PATH '$')
                ) AS j
            )
            WHERE r.id = :review_id
        ";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':review_id' => $review_id]);
    }

    public function update($id, $data){
    $stmt = $this->db->conn->prepare('UPDATE scores SET scorecard = :scorecard, review_id = :review_id');
        $stmt->execute([
            'scorecard' => json_encode($data['scorecard']),
            'review_id' => $data['review_id'],
            'id' => $id
        ]);
    }

    public function delete($id){
        $stmt = $this->db->conn->prepare('DELETE FROM scores WHERE id = :id');
        $stmt->execute(['id'=> $id]);
    }
}