<?php

namespace app\widgets;

use app\models\Score;

class ScoreWidget {
    public static function renderButton($id){
        $scores = new \app\models\Score;
        $scores = $scores->getScore($id);
        $decoded = json_decode($scores['scorecard'], true);

        return "
            
        ";
    }
}