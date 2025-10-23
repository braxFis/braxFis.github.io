<?php

namespace app\widgets;

use app\models\Read;

class ReadWidget {
    
    public static function renderButton($articleId) {
        return "
                <form method='POST' action='/read_add'>
                    <input type='hidden' name='article_id' value='$articleId'>
                    <button type='submit' class='save-btn'>📘 Läs senare</button>
                </form>
                ";
    }

    public static function renderList($userId) {

        $readModel = new Read();
        $articles = $readModel->getReadList(9);

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