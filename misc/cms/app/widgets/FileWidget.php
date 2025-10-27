<?php

namespace app\widgets;

use app\models\File;

class FileWidget {
    public static function renderFilePicker($id){
        $file = new File();
        $files = $file->getFile($id);
        $html = <<<HTML
        <input type="file" name="file" id="file" value="{$files->name}">
        HTML;
        return $html;
    }
}