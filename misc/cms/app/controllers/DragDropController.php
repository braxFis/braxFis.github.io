<?php
namespace app\controllers;

use app\models\DragDrop;

class DragDropController {
    private $model;

    public function __construct() {
        $this->model = new \app\models\DragDrop;
    }

    // Visa editorn (kan inkluderas i t.ex. Page-create)
    public function editor($slug = null) {
        if (!$slug) {
            $slug = "temp-" . uniqid();
        }
        $layout = $slug ? $this->model->getLayout($slug) : '[]';
        ob_start();
        require __DIR__ . '/../views/dragdrop/editor.php';
        return ob_get_clean(); // returneras som HTML-snutt
    }

    // Spara layout via AJAX
    public function save() {
        error_log("DragDropController::save() körs");
        $raw = file_get_contents('php://input');
        error_log("RAW DATA: " . $raw);

        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw, true);

        if (!isset($decoded['slug'], $decoded['layout'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data saknas']);
            return;
        }

        $saved = $this->model->saveLayout($decoded['slug'], $decoded['layout']);
        error_log("Sparar layout för slug={$decoded['slug']}");

        echo json_encode([
            'success' => $saved,
            'message' => $saved ? 'Layout sparad!' : 'Kunde inte spara layout.'
        ]);
    }

    public function load(){
        echo json_encode(['layout' => '[]']);
    }
}
