<?php

namespace app\widgets;

use app\models\Picture;

class PictureWidget {
    public static function renderImageSideBar($id, $page = 1) {
        $imageModel = new Picture();
        $screenshots = $imageModel->getScreenshots($id);
        
        if (empty($screenshots)) {
            return "<p>No screenshots found</p>";
        }

        $html = "<div class='screen-container'><strong>Screenshots</strong>";
        foreach ($screenshots as $shot) {
            $src = htmlspecialchars($shot['image'], ENT_QUOTES);
            $html .= "<a href='{$src}' target='_blank'>";
            $html .= "<img src='{$src}' width='200' height='200'>";
            $html .= "</a>";
        }
        $html .= "</div>";

        $html .= "<button class='load-more' data-page='{$page}'>Visa fler</button>";

        return $html;
    }

}