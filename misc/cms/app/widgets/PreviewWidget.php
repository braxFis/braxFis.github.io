<?php

namespace app\widgets;

use app\models\Preview;
use app\templates\Tabulate;

class PreviewWidget extends Tabulate{
    public static function renderTop100(){
        $preview = new Preview();
        $previews = $preview->getTop100Previews();

        if(empty($previews)){
            return "<p>No previews found</p>";
        }

        $html = "<div class='preview-container'>";
        foreach($previews as $preview){
            $html .= "div class='review-inner'>";
            $html .= "<img src='{$preview->media}' width='200' height='200'/>";
            $html .= "<h1>{$preview->title}</h1>";
            $html .= "<button>View</button";
            $html .= "</div>";
        }
            $html .= "</div>";
            
            return $html;
    }
}