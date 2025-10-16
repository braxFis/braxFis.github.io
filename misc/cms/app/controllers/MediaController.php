<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;

require_once __DIR__ . '/../models/Media.php';

class MediaController extends BaseController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\Media;
    }

    public function index(){
        $medias = $this->model->getMedias();
        $mediaTypes = $this->model->getMediaTypes1(); // <– safe to use here
        $mediaCategories = $this->model->getMediaTypes2();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require_once __DIR__ . '/../views/media/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

    public function create($data){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = [
                //add the data manually
                'title' => isset($_POST['title']) ? $_POST['title'] : null,
                'media_type' => isset($_POST['media_type']) ? $_POST['media_type'] : null,
                'url'  => isset($_POST['url']) ? $_POST['url'] : null,
                'related_type' => isset($_POST['related_type']) ? $_POST['related_type'] : null,
                'related_id' => isset($_POST['related_id']) ? $_POST['related_id'] : null,
                'uploaded_by' => isset($_POST['uploaded_by']) ? $_POST['uploaded_by'] : null,
                'uploaded_at' => isset($_POST['uploaded_at']) ? $_POST['uploaded_at'] : null,
            ];
            $mediaModel = new app\models\Media;
            $medias = $mediaModel->create($data);
            $media = $mediaModel->create($data);
        } else{
            $mediaTypes = $this->model->getMediaTypes1(); // <– safe to use here
            $mediaCategories = $this->model->getMediaTypes2();
            $menus = (new Menu)->getMenuItems();
            $footers = (new Footer)->getFooterItems();
            ob_start();
            require __DIR__ . '/../views/media/create.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layout.php';
        }
    }

    public function store($postData)
    {
        $this->requireAdmin();

        // Check for file upload
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image_url']['tmp_name'];
            $fileName = $_FILES['image_url']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mov', 'avi', 'mkv'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                die('Invalid file extension.');
            }

            $newFileName = uniqid('media_', true) . '.' . $fileExtension;
            $uploadDir = __DIR__ . '/../../public/img/';
            $destPath = $uploadDir . $newFileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                die('Failed to move uploaded file.');
            }

            // Set the uploaded file URL
            $postData['url'] = '/img/' . $newFileName;

        } else {
            die('No image uploaded.');
        }

        // Pass to model
        $this->model->create($postData);
        header('Location: /media');
        exit;
    }

    public function edit($id){
        $this->requireAdmin();
        $media = $this->model->getMedia($id);
        $mediaTypes = $this->model->getMediaTypes1(); // <– safe to use here
        $mediaCategories = $this->model->getMediaTypes2();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require_once __DIR__ . '/../views/media/edit.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

    public function update($data, $id){
        $this->requireAdmin();
        if (empty($_POST['title']) || empty($_POST['media_type']) || empty($_POST['url'])) {
            die('Empty fields are required.');
        }
        $this->model->updateMedia($id, $data);
        header('Location: /media');
        exit;
    }

    public function delete($id){
        $this->requireAdmin();
        $this->model->deleteMedia($id);
        header('Location: /media');
        exit;
    }
}
