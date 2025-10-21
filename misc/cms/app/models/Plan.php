<?php

namespace app\models;

use app\controllers\RegularController;
use app\models\Standard;
use app\models\Premium;

require_once __DIR__ . '/../../bootstrap.php';

class Plan{
  private $db;
  public function __construct()
  {
    $this->db = new \Database;
  }
  public function getPlan($id){
    $stmt = $this->db->conn->prepare("SELECT * from plan WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_OBJ);
  }
  public function getPlans(){
    $stmt = $this->db->conn->prepare("SELECT * FROM plan");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }
  public function addPlan($data){
    $stmt = $this->db->conn->prepare("INSERT INTO plan(name, email, phone, address, type) VALUES(:name, :email, :phone, :address, :type)");
    $stmt->execute([
      'name' => $data['name'],
      'email' => $data['email'],
      'phone' => $data['phone'],
      'address' => $data['address'],
      'type' => $data['type']
    ]);
  }
  public function editPlan(){}
  public function updatePlan($data, $id){
    $stmt = $this->db->conn->prepare("UPDATE plan SET name = :name, email = :email, phone = :phone, address = :address, type = :type WHERE id = :id");
    $stmt->execute([
      'name' => $data['name'],
      'email' => $data['email'],
      'phone' => $data['phone'],
      'address' => $data['address'],
      'type' => $data['type'],
      'id' => $id
    ]);
  }
  public function deletePlan($id){
    $stmt = $this->db->conn->prepare("DELETE FROM plan WHERE id = :id");
    $stmt->execute(['id' => $id]);
  }
  public function freePlan():RegularController{
    return self::freePlan();
  }
  public function standardPlan(){
    (new Standard())->audio();
  }
  public function premiumPlan(){
    (new Premium())->interviews();
  }

}
