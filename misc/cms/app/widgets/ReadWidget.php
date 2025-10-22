<?php

namespace app\widgets;

use app\models\Read;

class ReadWidget {
    public static function renderButton($articleId) {
        return "<button class='save-btn' data-id='{$articleId}'>📘 Läs senare</button>";
    }

    public static function renderList($userId) {

        $readModel = new Read();
        $articles = $readModel->getReadList($userId);

        if (!$articles) {
            return "<p>Du har inga sparade artiklar.</p>";
        }

        $html = "<ul class='read-list'>";
        foreach ($articles as $article) {
            $html .= "<li><a href='/news/indie/{$article['id']}'>{$article['title']}</a></li>";
        }
        $html .= "</ul>";

        return $html;
    }
}