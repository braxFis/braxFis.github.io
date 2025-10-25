<?php

namespace app\widgets;

use app\models\Share;

class ShareWidget{
    public static function renderShare(Share $share){
        //return Html::tag("", $share->title, []);
        return 
            '
            <button class="share-button" data-platform="facebook">Share on Facebook</button>
            <button class="share-button" data-platform="twitter">Share on Twitter</button>
            <button class="share-button" data-platform="linkedin">Share on LinkedIn</button>
            ';
    }
}