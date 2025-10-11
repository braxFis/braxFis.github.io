<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Gallery.php';

class GalleryController extends BaseController{
    private $model;
    
    public function __construct(){
        $this->model = new \app\models\Gallery;
    }

    public function getGallery(){
        $images = $this->model->getGallery();
        require __DIR__ . '/../views/gallery/index.php';
    }
}