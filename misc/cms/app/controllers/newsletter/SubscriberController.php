<?php

namespace app\controllers\newsletter;

use app\controllers\BaseController;
use app\helpers\UploadHelper;
use app\models\Footer;
use app\models\Menu;

require_once __DIR__ . '/../../models/newsletter/Subscriber.php';

class SubscriberController extends BaseController{
    private $model;

    public function __construct()
    {
        $this->model = new \app\models\newsletter\Subscriber;
    }

    public function index(){
        $subscribers = $this->model->getSubscribers();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../../views/subscriber/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/layout.php';
    }

    public function create(){
        $this->requireAdmin();
        $subscriberModel = new \app\models\newsletter\Subscriber;
        $subscribers = $subscriberModel->getSubscribers();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../../views/subscriber/create.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/layout.php';
    }

    public function store($postData)
    {
        $this->requireAdmin();
        $imageUrl = (new UploadHelper)->handleImageUpload();
        if ($imageUrl) {
            $postData['image'] = $imageUrl;
        }
        $this->model->addSubscriber($postData);
        header('Location: /subscriber');
    }

    public function edit($id){
        $this->requireAdmin();
        $subscriber = $this->model->getSubscriber($id);
        require __DIR__ . '/../../views/subscriber/edit.php';
    }

    public function update($data, $id){
        $this->requireAdmin();
        $imageUrl = (new UploadHelper)->handleImageUpload();
        if ($imageUrl) {
            $postData['image'] = $imageUrl;
        }
        $this->model->updateSubscriber($id, $data);
        header('Location: /subscriber');
        exit;
    }

    public function delete($id){
        $this->requireAdmin();
        $this->model->removeSubscriber($id);
        header('Location: /subscriber');
        exit;
    }

}