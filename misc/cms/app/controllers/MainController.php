<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Main.php';

use app\models\Chapter;
use app\models\Objective;
use app\models\Menu;
use app\models\Footer;
use app\models\Gallery;
use app\models\News;
use app\models\Post;
use app\models\Review;
use app\models\Preview;

class MainController extends BaseController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\Main;
    }

    public function index($id, $postData = null){}
}