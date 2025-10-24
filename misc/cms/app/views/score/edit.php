<form action="/scores/update/<?= $scores['id']?>" method="POST">
    <input type="hidden" name="id" id="<?= $scores['id']?>">
    <div>
<?php
    $decoded = json_decode($scores['scorecard'], true);

if (!empty($decoded) && is_array($decoded)) {
    foreach ($decoded as $category => $fields) {
        echo "<fieldset>";
        echo "<legend>" . ucfirst($category) . "</legend>";

        foreach ($fields as $field => $value) {
            echo '<label>' . ucfirst(str_replace('_',' ',$field)) . '</label>';
            echo '<input type="number" name="scorecard['.$category.']['.$field.']" value="'.$value.'" min="0" max="10"><br>';
        }

        echo "</fieldset>";
    }
} else {
    echo "<p>Inga poäng hittades.</p>";
}?>
    </div>
    <button type="submit">Edit Score</button>
</form>