<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;
use app\models\Plan;

require_once __DIR__ . '/../models/Plan.php';

class PlanController extends BaseController{

  private $model;

  public function __construct(){
    $this->model = new Plan;
  }

  public function index(){
    $plans = $this->model->getPlans();
    ob_start();
    require_once __DIR__ . '/../views/plans/index.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function create(){
    $this->requireAdmin();
    $plans = $this->model->getPlans();
    ob_start();
    require_once __DIR__ . '/../views/plans/create.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function store($postData){
    $this->requireAdmin();
    $this->model->addPlan($postData);
    header('Location: /plan');
  }

  public function edit($id){
    $this->requireAdmin();
    $plan = $this->model->getPlan($id);
    ob_start();
    require_once __DIR__ . '/../views/plans/edit.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->updatePlan($id, $data);
    header('Location: /plan');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->deletePlan($id);
    header('Location: /plan');
    exit;
  }


}
