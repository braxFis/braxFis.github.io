<?php

namespace app\widgets;

use app\models\Review;
use app\templates\Tabulate;

class ReviewWidget extends Tabulate{
    public static function renderTop100(){
        $review = new Review();
        $reviews = $review->getTop100Reviews();

        if(empty($reviews)){
            return "<p>No reviews found</p>";
        }

        $html = "<div class='review-container'>";
        foreach($reviews as $review){
            $html .= "div class='review-inner'>";
            $html .= "<img src='{$review->media}' width='200' height='200'/>";
            $html .= "<h1>{$review->title}</h1>";
            $html .= "<button>View</button";
            $html .= "</div>";
        }
            $html .= "</div>";
            
            return $html;
    }
}