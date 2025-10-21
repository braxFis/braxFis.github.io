<?php

class Database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "pezDispenser1!";
    private $dbname = "gd_data";

    public $conn;
    public function __construct(){
        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            //echo "Connected successfully";
        } catch (Exception $ex) {
            //echo $ex->getMessage();
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }
}
