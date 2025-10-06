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
  }
}
