<?php

namespace app\controllers;

//use app\models\Footer;
//use app\models\Menu;

use app\models\User;
use app\controllers\BaseController;

require_once __DIR__ . "/../models/User.php";

class UserController{
  private $model;

  public function __construct(){
    $this->model = new User;
  }

  public function login($data){
    $login = $this->model->login($_POST);
    if($login['status'] === 'success'){
      header('Location: /profile');
      exit;
    }
    //$menus = (new Menu)->getMenuItems();
    //$footers = (new Footer)->getFooterItems();
    //ob_start();
    require __DIR__ . "/../views/user/login.php";
    //$content = ob_get_clean();
    //require __DIR__ . '/../views/layout.php';
  }

  public function logout() {
    ob_start();
    if (!isset($_SESSION['user_id'])) {
      header('Location: /login');
      exit;
    }

    session_unset();
    session_destroy();
    header('Location: /login');
    exit;
  }
  public function register($data){
    $result = $this->model->register($_POST);

    if ($result['status'] === 'success') {
      header('Location: /login');
      exit;
    }
    //$menus = (new Menu)->getMenuItems();
    //$footers = (new Footer)->getFooterItems();
    //ob_start();
    // Stay on the register page and show error
    require __DIR__ . "/../views/user/register.php";
    //$content = ob_get_clean();
    //require __DIR__ . '/../views/layout.php';
  }


}
