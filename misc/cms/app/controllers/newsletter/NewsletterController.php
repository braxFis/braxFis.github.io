<?php

namespace app\controllers\newsletter;

use app\controllers\BaseController;
use app\helpers\UploadHelper;
use app\models\Footer;
use app\models\Menu;

require_once __DIR__ . '/../../models/newsletter/Newsletter.php';

class NewsletterController extends BaseController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\newsletter\Newsletter;
    }

    public function index(){
        $newsletters = $this->model->getAll();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../../views/newsletter/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout.php';
    }

    public function create(){
        $this->requireAdmin();
        $newsletterModel = new \app\models\newsletter\Newsletter;
        $newsletters = $newsletterModel->getAll();

        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../../views/newsletter/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout.php';
    }

    public function store($postData){
        $this->requireAdmin();
        $imageUrl = (new UploadHelper)->handleImageUpload();
        if($imageUrl){
            $postData['image'] = $imageUrl;
        }
        $this->model->create($postData);
        header('Location: /newsletter');
    }

    public function edit($id){
        $this->requireAdmin();
        $newsletter = $this->model->getOne($id);
        require __DIR__ . '/../../views/newsletter/edit.php';
    }

    public function update($data, $id){
        $this->requireAdmin();
        $imageUrl = (new UploadHelper)->handleImageUpload();
        if($imageUrl){
            $postData['image'] = $imageUrl;
        }
        $this->model->updateNewsletter($id, $data);
        header('Location: /newsletter');
        exit;
    }

    public function delete($id){
        $this->requireAdmin();
        $this->model->deleteNewsletter($id);
        header('Location: /newsletter');
        exit;
    }

    public function send($id){
        $this->requireAdmin();
        echo "Triggered send with ID: $id";
        $newsletter = $this->model->getOne($id);
        if(!$newsletter){
            echo "Newsletter not found";
            return;
        }

        if($newsletter->status == 'sent'){
            echo "Newsletter already sent";
            return;
        }

        //Load subs
        $subscriberModel = new \app\models\newsletter\Subscriber;
        $subscribers = $subscriberModel->getSubscribers();

        $subject = $newsletter->title;
        $message = $newsletter->body;
        $headers = "From: nickgatsfield@gmail.com";
        $headers .= "Content-Type: text/html";
        $sentCount = 0;
        foreach($subscribers as $subscriber){
            if(mail($subscriber->email, $subject, $message, $headers)){
                $sentCount++;
            }
        }

        $this->model->markAsSent($id);

        echo "Newsletter sent to $sentCount subscribers";
    }
}