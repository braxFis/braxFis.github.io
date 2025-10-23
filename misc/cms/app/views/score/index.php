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
}
?>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
      <li style="list-style-type: none">
        <button><a style="text-decoration: none; color: white" href="/scores/edit/<?php echo $scores['id'];?>">Edit Score</a></button>
        <!-- Delete Review -->
        <form action="/scores/delete/<?php echo $scores['id'];?>" method="post">
          <input type="hidden" name="id" id="id" value="<?php echo $scores['id'];?>">
          <button type="submit">Delete Score</button>
        </form>
      </li>
    <?php endif;?>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <button><a style="text-decoration: none; color: white;" href="/scores/create">Create Score</a></button>
  <?php endif;?>