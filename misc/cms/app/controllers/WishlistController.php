<?php

namespace app\controllers;

use app\models\Menu;
use app\models\Footer;
use app\interfaces\FeatureController;
use app\templates\Model;
use app\models\Wishlist;
// ----------------------------
// Wishlist
// ----------------------------
class WishlistController extends BaseController implements FeatureController, Model {

    private $model;
    public function __construct() {
        $this->model = new \app\models\Wishlist;
    }

    public function index(){
        $wishlists = $this->model->getWishlists();
        ob_start();
        require_once __DIR__ . '/../views/wishlist/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function store($postData){
        $wishlist = new \app\models\Wishlist;

        $saved = $this->model->create(
            $postData
        );

        header('Location: /feature/wishlist');
    }

    public function create(){
        $this->requireAdmin();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require_once __DIR__ . '/../views/wishlist/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function edit($id){
    $wishlist = $this->model->getWishlist($id);
    ob_start();
    require __DIR__ . '/../views/wishlist/edit.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->update($id, $data);
    header('Location: /feature/wishlist');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->delete($id);
    header('Location: /feature/wishlist');
    exit;
  }


}