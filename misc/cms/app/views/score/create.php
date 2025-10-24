<h1>Create Score</h1>
<form method="POST" action="/scores/store">
  <input type="hidden" name="review_id" value="<?= $review_id ?>">
<?php foreach ($data as $category => $fields): ?>
  <fieldset>
    <legend><?= ucfirst($category) ?></legend>
    <?php foreach ($fields as $field): ?>
      <label><?= $field ?></label>
      <input type="number" name="scorecard[<?= $category ?>][<?= strtolower(str_replace(' ', '_', $field)) ?>]" min="0" max="10">
    <?php endforeach; ?>
  </fieldset>
<?php endforeach; ?>
<button type="submit">Spara</button>
</form>