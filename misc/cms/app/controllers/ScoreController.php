<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Score.php';

class ScoreController extends BaseController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\Score;
    }

    public function index($id){
        $scores = $this->model->getScore($id);
        ob_start();
        require_once __DIR__ . '/../views/score/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function create($id){
        $this->requireAdmin();
        ob_start();
        $data = include __DIR__ . '/../../config/score_data.php';
        require_once __DIR__ . '/../views/score/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function edit($id){
        $scores = $this->model->getScore($id);
        ob_start();
        require_once __DIR__ . '/../views/score/edit.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function update($id, $data){
        $this->requireAdmin();
        $this->model->update($id, $data);
        header('Location: /scores');
        exit;        
    }

    public function delete($id){
        $this->requireAdmin();
        $this->model->delete($id);
        header('Location: /scores');
        exit;
    }

    public function store($postData){
        $this->requireAdmin();

        // Skapa ny News-instans
        $scores = new \app\models\Score;

        // Försök spara
        $saved = $this->model->create($postData);

        if ($saved) {
            header('Location: /scores');
            exit;
        } else {
            echo "Kunde inte skapa sidan.";
        }
    }
}