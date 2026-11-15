<?php
declare(strict_types=1);
namespace app\controllers;

use app\services\WordPressMirror;

final class ArticleController
{
    public function index(): void
    {
        try {
            $mirror = WordPressMirror::configured();
            $all = $mirror ? $mirror->articles() : [];
            $page = max(1, (int) ($_GET['page'] ?? 1));
            $pages = max(1, (int) ceil(count($all) / 20));
            if ($page > $pages) { http_response_code(404); echo 'Sidan hittades inte.'; return; }
            $articles = array_slice($all, ($page - 1) * 20, 20);
            ob_start();
            require __DIR__ . '/../views/articles/index.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layout.php';
        } catch (\Throwable $error) {
            error_log('WordPress mirror unavailable: ' . get_class($error));
            http_response_code(503);
            echo 'Artiklarna är tillfälligt otillgängliga.';
        }
    }

    public function show($id): void
    {
        try {
            $mirror = WordPressMirror::configured();
            $article = null;
            foreach ($mirror ? $mirror->articles() : [] as $candidate) {
                if ((string) $candidate['id'] === (string) $id) { $article = $candidate; break; }
            }
            if ($article === null) { http_response_code(404); echo 'Artikeln hittades inte.'; return; }
            ob_start();
            require __DIR__ . '/../views/articles/show.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layout.php';
        } catch (\Throwable $error) {
            error_log('WordPress mirror unavailable: ' . get_class($error));
            http_response_code(503);
            echo 'Artikeln är tillfälligt otillgänglig.';
        }
    }
}
