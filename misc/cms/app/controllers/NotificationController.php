<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new \app\models\Notification;
  }

  public function Notification(){
    $notification = $this->model->getNotification();
    ob_start();
    require_once __DIR__ . '/../views/notifications/index.php';
    $content = ob_get_clean();
    require __DIR__ . '/../views/layout.php';

  }
}
