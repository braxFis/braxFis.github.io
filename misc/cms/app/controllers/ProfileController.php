<?php

namespace app\controllers;

use app\models\Footer;
use app\helpers\UploadHelper;
use app\models\Menu;
use app\models\Profile;

require_once __DIR__ . "/../models/Profile.php";
require_once __DIR__ . '/../helpers/UploadHelper.php';

class ProfileController extends BaseController {
  private $model;

  public function __construct(){
    $this->model = new Profile;
  }

  public function viewProfile($id = null){
    if (!$id && isset($_SESSION['user_id'])) {
      $id = $_SESSION['user_id'];
    }

    if (!$id) {
      header("Location: /login");
      exit;
    }

   $imageUrl = (new UploadHelper)->handleImageUpload();
    if ($imageUrl) {
      $postData['image'] = $imageUrl;
    }
    $profile = $this->model->view($id);
    $menus = (new Menu)->getMenuItems();
    $footers = (new Footer)->getFooterItems();
    ob_start();
    require __DIR__ . "/../views/profile/index.php";
    $content = ob_get_clean();
    require __DIR__ . "/../views/layout.php";
  }

  public function editProfile(){
    //$this->requireUser();
    $profile = $this->model->edit();
    require __DIR__ . "/../views/profile/index.php";
  }

  public function updateProfile($data){
    $imageUrl = (new UploadHelper)->handleImageUpload();
    if ($imageUrl) {
      $data['image'] = $imageUrl;
    }
    $profile = $this->model->update($_SESSION['user_id'], $data);
    header('Location: /profile');
    exit;
  }

  public function deleteProfile(){
    //$this->requireUser();
    $this->model->delete($_SESSION['user_id']);
    session_destroy();
    header('Location: /');
    exit;
  }

  public function confirmDelete(){
    require __DIR__ . "/../views/profile/delete.php";
  }
}
