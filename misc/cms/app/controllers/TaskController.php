<?php
namespace app\controllers;

use app\models\Task;

class TaskController {
    private $model;

    public function __construct($db) {
        $this->model = new Task($db);
    }

    public function index() {
        $tasks = $this->model->all();
        require __DIR__ . '/../views/tasks/index.php';
    }

    public function store($data) {
        $this->model->create($data['title'], $data['description'], $data['due_date']);
        header("Location: /tasks");
    }

    public function delete($id) {
        $this->model->delete($id);
        header("Location: /tasks");
    }

    public function edit($id) {
        $task = $this->model->get($id);
        require __DIR__ . '/../views/tasks/edit.php';
    }

    public function update($id, $data) {
        $this->model->update($id, $data['title'], $data['description'], $data['due_date']);
        header("Location: /tasks");
    }
}
