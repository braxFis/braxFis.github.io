<?php

namespace app\widgets;

use app\models\Like;

class LikeWidget{
    public static function renderLike(){
        return 
        "
            <button id='likeBtn' class='like'>Like</button>
            <button id='dislikeBtn' class='dislike'>Dislike</button>
        ";
    }
}