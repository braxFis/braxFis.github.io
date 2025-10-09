<?php

namespace app\controllers;

use app\models\Comment;

require_once  __DIR__ . "/../models/Comment.php";

class CommentController extends BaseController{

    private $model;

    public function __construct(){
        $this->model = new \app\models\Comment;
    }

    public function store($data) {

        if(!isset($_SESSION['user_id'])){
            die('You are not logged in');
        }
        $postId = isset($data['news_id']) ? $data['news_id'] : (isset($data['id']) ? $data['id'] : null);
        $body = isset($data['body']) ? $data['body'] : null;
        $userId = $_SESSION['user_id'];

        if (!$postId || !$body) {
            echo "❌ Missing post_id or body";
            var_dump($data); // 👈 ADD THIS TO DEBUG
            return;
        }

        $this->model->createComment($postId, $body, $userId);
        header("Location: /news/indie/$postId");
        exit;
    }

    public function edit($id){
        if(!isset($_SESSION['user_id'])){
            die('You are not logged in');
        }
        //GET THE COMMENT
        $comment = $this->model->getComment($id);
        //Check if the comment exists
        if (!$comment) {
            $_SESSION['message'] = 'error, Comment not found';
            header("Location: /posts/show/" . $comment->post_id);
            exit;
        }

        // Create an instance of the Comment model
        $postModel = new \app\models\News;
        // Get the post by id
        $post = $postModel->getNew($comment->post_id);
        // Check if the post exists
        if(!$post){
            $_SESSION['message'] = 'error, Post not found';
            header("Location: /posts/show/" . $comment->post_id);
            exit;
        }
        //Load the view
        require BASE_DIR . '/app/views/admin/comments/edit.php';
    }

    public function update($data, $id){
        $data = $_POST;
        if(!isset($_SESSION['user_id'])){
            die('You are not logged in');
        }
        $result = $this->model->updateComment($id, $data);
        // Update the comment in the database
        if(!empty($data['post_id'])){
            $postId = $data['post_id'];
            $_SESSION['message'] = 'Comment updated successfully.';
            header("Location: /posts/show/" . $postId);
        } else {
            header("Location: /posts");
        }
    }

    public function delete($id){
        if(!isset($_SESSION['user_id'])){
            die('Access denied');
        }
        // Delete the comment from the database
        $this->model->deleteComment($id);
        // Redirect to the post page
        header("Location: /posts");
    }
}
