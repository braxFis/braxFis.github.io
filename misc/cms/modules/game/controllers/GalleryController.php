<?php
namespace modules\game\controllers;

use modules\game\models\Gallery;

class GalleryController
{
    public function index()
    {
        $model = new Gallery();
        $images = $model->getGallery();
    }
}