<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Home.php';

class HomeController extends BaseController{
    private $model;
    
    public function __construct(){
        $this->model = new \app\models\Home;
    }
}