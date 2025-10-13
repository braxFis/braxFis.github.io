<?php

namespace app\controllers;

use app\helpers\UploadHelper;
use app\models\Footer;
use app\models\Menu;

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../helpers/UploadHelper.php';
class PostController{

    private $model;

    public function __construct(){
        $this->model = new \app\models\Post;
    }
    public function index(){
        $posts = $this->model->getPosts();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../views/posts/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }
    public function show($id){
        $post = $this->model->getPost($id);
        if ($post === null){
            require __DIR__ . '/../views/404.php';
            return;
        }
        $commentModel = new \app\models\Comment;
        $comments = $commentModel->getComments($id);
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../views/posts/show.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

    public function update($postData, $id)
    {
        $this->requireAdmin();

        // Handle image upload
        $uploadHelper = new UploadHelper();
        $imagePath = $uploadHelper->handleImageUpload(); // returns null if no new image

        // If no new image uploaded, fetch old image from DB
        if (!$imagePath) {
            $existing = $this->model->find($id);
            $imagePath = $existing['image'] ?? null;
        }

        // Add image to $postData for consistency
        $postData['image'] = $imagePath;

        // Call the update with the full $postData and id
        $this->model->update($id, $postData);

        header('Location: /posts'); // or wherever it should go
        exit;
    }

    public function search(){
        $query = isset($_GET['query']) ? $_GET['query'] : null;
        $posts = [];
        if(!empty($query)){
            $posts = $this->model->searchPosts($query);
        }
        require __DIR__ . '/../views/posts/search.php';
    }


}
