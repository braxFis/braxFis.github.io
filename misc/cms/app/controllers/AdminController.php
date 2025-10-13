<?php

namespace app\controllers;

use app\helpers\UploadHelper;
use app\models\Footer;
use app\models\Menu;
use app\models\Post;
use PDOException;

require_once __DIR__ . '/../models/Post.php';

class AdminController extends BaseController {
    private $model;

    public function __construct(){
        $this->model = new Post();
    }

    public function create()
    {
        $this->requireAdmin();
        $categoryModel = new \app\models\Category;
        $categories = $categoryModel->getCategories();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require_once __DIR__ . '/../views/admin/posts/create.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

    public function store($postData) {
        $title = isset($postData['title']) ? $postData['title'] : null;
        $content = isset($postData['content']) ? $postData['content'] : null;
        $category_id = isset($postData['category_id']) ? $postData['category_id'] : null;
        $imagePath = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $fileType = $_FILES['image']['type'];
            $allowedTypes = ['image/jpeg', 'image/png'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (in_array($fileType, $allowedTypes) && $_FILES['image']['size'] <= $maxSize) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $uniqueName = uniqid() . "_" . $fileName;
                $destPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $imagePath = $uniqueName;
                }
            }
        }
        $this->model->createPost($title, $content, $category_id, $imagePath);
        header("Location: /posts");
        exit;
    }



    public function index(){
        //Retreive all posts
        $posts = $this->model->getPosts();
        //Load the admin post view
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../views/admin/posts/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

    public function update($postData, $id){
        $this->requireAdmin();
        //Retreive form data
        $title = $postData['title'];
        $content = $postData['content'];
        $category_id = $postData['category_id'];
        $imagePath = $postData['image'];
        try {
            $this->model->update($id, $title, $content, $category_id, $imagePath);
            $_SESSION['message'] = "Post updated successfully";
            header('Location: /posts');
            exit;
        } catch (\Exception $exception) {
            echo $_SESSION['message'] = $exception->getMessage();
            exit;
        }
    }

    public function delete($id){
        $this->requireAdmin();
        $this->model->deletePost($id);
        header('Location: /posts');
    }

    public function edit($id){
        $this->requireAdmin();
        $post = $this->model->getPost($id);
        // Fetch all categories
        $categoryModel = new \app\models\Category;
        $categories = $categoryModel->getCategories();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../views/admin/posts/edit.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

    public function show($id){
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $fileType = mime_content_type($fileTmpPath);
            $allowedTypes = ['image/jpeg', 'image/png'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (in_array($fileType, $allowedTypes) && $_FILES['image']['size'] <= $maxSize) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $uniqueName = uniqid() . "_" . $fileName;
                $destPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $imagePath = $uniqueName;
                }
            }
        }

        $post = $this->model->getPost($id);
        if ($post === null) {
            require BASE_DIR . 'app/views/404.php';
            return;
        }
        // Create an instance of the Comment model
        $commentModel = new \app\models\Comment;
        // Retreive all comments for the post
        $comments = $commentModel->getComments($id);
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../views/admin/posts/show.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }
}