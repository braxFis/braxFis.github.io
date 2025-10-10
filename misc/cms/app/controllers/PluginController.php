<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;
use app\models\Plugin;
use app\core\PluginManager;
use ZipArchive;

require_once __DIR__ . '/../models/Plugin.php';
require_once __DIR__ . '/../../core/PluginManager.php';
class PluginController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new Plugin;
  }

  public function index(){
    $plugins = $this->model->getPlugins();
    $menus = (new Menu)->getMenuItems();
    $footers = (new Footer)->getFooterItems();
    ob_start();
    require __DIR__ . '/../views/plugins/index.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function install(){
    $this->requireAdmin();
    $plugins = $this->model->getPlugins();
    $menus = (new Menu)->getMenuItems();
    $footers = (new Footer)->getFooterItems();
    ob_start();
    require_once __DIR__ . '/../views/plugins/install.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function edit($id){
    $this->requireAdmin();
    $plugin = $this->model->getPlugin($id);
    $menus = (new Menu)->getMenuItems();
    $footers = (new Footer)->getFooterItems();
    ob_start();
    require_once __DIR__ . '/../views/plugins/edit.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->update($id, $data);
    if ($data === null) {
      $data = $_POST;
    }
    if(empty($_POST['title']) || empty($_POST['content']) || empty($_POST['image'])){
      die('Fill in all fields');
    }
    header('Location: /plugins');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->delete($id);
    header('Location: /plugins');
    exit;
  }

  public function store($postData){
    $this->requireAdmin();

    if (!isset($_POST['name'], $_POST['active'], $_FILES['zip'])) {
      die("Alla fält måste fyllas i.");
    }

    $name = preg_replace('/[^a-zA-Z0-9_-]/', '',$_POST['name']);
    $active = (int) $_POST['active'];
    $zipFile = $_FILES['zip'];

    // Kontrollera zip
    if (pathinfo($zipFile['name'], PATHINFO_EXTENSION) !== 'zip') {
      die("Endast zip-filer är tillåtna.");
    }

    // Skapa pluginmapp
    $pluginDir = __DIR__ . '/../plugins/' . $name;
    if (!is_dir($pluginDir)) mkdir($pluginDir, 0755, true);

    // Packa upp
    $zip = new ZipArchive;
    if ($zip->open($zipFile['tmp_name']) === TRUE) {
      $zip->extractTo($pluginDir);
      $zip->close();
    } else {
      die("Fel vid uppackning av zip.");
    }

    // 🔧 Nytt: Flytta controller/model/view till rätt mappar
    $manager = new \PluginManager();
    $manager->movePluginParts($pluginDir);

    // 🔧 Spara till databasen
    $this->model->install($_POST);

    //header('Location: /plugins');
    exit;
  }
}
