<?php
declare(strict_types=1);

namespace modules\guide\controllers;

use modules\guide\models\Guide;

/** Walkthrough routes; shares Guide model with game pages. */
final class GuideController
{
    private Guide $guides;

    public function __construct()
    {
        $this->guides = new Guide();
    }

    /** /games/{rawgId}/guides */
    public function forGame($gameId): void
    {
        $id = $this->validGameId($gameId);
        if ($id === null) {
            $this->notFound();
            return;
        }

        $guides = $this->guides->forGame($id, $this->showDrafts());
        $this->render('index.php', compact('id', 'guides'));
    }


  
    public function show($gameId, $slug): void
    {
        $id = $this->validGameId($gameId);

        if ($id === null) {
            $this->notFound();
            return;
        }

        // Tillåt utkast under detta lokala test.
        $guide = $this->guides->find($id, $slug, true);

        if ($guide === null) {
            echo '<pre>';
            echo 'find() returned null';
            echo '</pre>';
            return;
        }

        $this->render('show.php', [
            'id' => $id,
            'guide' => $guide
        ]);
    }

    private function validGameId($value): ?int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        return $id === false ? null : $id;
    }

    private function showDrafts(): bool
    {
        // Explicit opt-in for local testing; never show drafts by default.
        return getenv('APP_ENV') === 'local';
    }

    private function render(string $view, array $vars): void
    {
        extract($vars, EXTR_SKIP);
        ob_start();
        require __DIR__ . '/../views/' . $view;
        $content = ob_get_clean();
        require __DIR__ . '/../../../app/views/layout.php';
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo 'Guide not found';
    }
}
