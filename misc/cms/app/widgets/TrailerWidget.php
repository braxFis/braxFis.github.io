<?php

namespace app\widgets;

use app\models\Trailer;

class TrailerWidget {

    public static function renderTrailerSideBar($id){
        $trailerModel = new Trailer();
        $trailers = $trailerModel->getTrailers($id);

        if(empty($trailers)){
            return "<p>No trailers found</p>";
        }

        $html = "<div class='trailer-container'><strong>Trailers</strong>";

        foreach($trailers as $trailer){
            $src = htmlspecialchars($trailer['name'], ENT_QUOTES);
            $preview = htmlspecialchars($trailer['preview'], ENT_QUOTES);
            $max = htmlspecialchars($trailer['max'], ENT_QUOTES);

            $html .= "<h4>{$src}</h4>";
            $html .= "<video width='640' height='320' controls poster='{$preview}'>";
            $html .= "<source src='{$max}' type='video/mp4'>";
            $html .= "Your browser does not support the video tag.";
            $html .= "</video>";
        }

        $html .= "</div>";
        return $html;
    }
}