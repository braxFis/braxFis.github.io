<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = $_POST['custom_fields'] ?? [];
    // $fields är en numerisk array med assoc-arrayer: [['key'=>'color','value'=>'red'], ...]
    foreach ($fields as $f) {
        $k = trim($f['key'] ?? '');
        $v = trim($f['value'] ?? '');
        if ($k === '' && $v === '') continue;
        // Spara logik här (file, db, json, etc)
        // exempel: echo "$k => $v\n";
    }
}
