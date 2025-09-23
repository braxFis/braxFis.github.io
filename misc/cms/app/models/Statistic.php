<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Statistic{
  private $db;

  private $model;

  public function __construct(){
    $this->db = new \Database;
  }

  public function showReviews(){
    $stmt = $this->db->conn->prepare("SELECT * FROM reviews");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function getReviewChartData(){
    $stmt = $this->db->conn->prepare("SELECT title, rating FROM reviews");
    $stmt->execute();
    $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

    $data = [['Title', 'Rating']]; // header
    foreach ($rows as $row) {
      $data[] = [$row->title, $row->rating];
    }

    return $data;
  }
}
