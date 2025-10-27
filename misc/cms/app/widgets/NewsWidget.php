<?php

namespace app\widgets;

use app\models\News;
use app\templates\Tabulate;

class NewsWidget extends Tabulate{
    public static function renderTop100(){
        $new = new News();
        $news = $new->getTop100News();

        if(empty($news)){
            return "<p>No news found</p>";
        }

        $html = "<div class='new-container'>";
        foreach($news as $new){
            $html .= "div class='new-inner'>";
            $html .= "<img src='{$new->media}' width='200' height='200'/>";
            $html .= "<h1>{$new->title}</h1>";
            $html .= "<button>View</button";
            $html .= "</div>";
        }
            $html .= "</div>";
            
            return $html;
    }
}