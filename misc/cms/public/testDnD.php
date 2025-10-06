
<?php
if (!isset($layout) || empty($layout)) {
  $layout = [
    "sidebar" => ["news","search"],
    "main" => [],
    "footer" => ["contact"]
  ];
}
var_dump($layout); // för debug
?>

<?php
$layout = $layout ?? ["sidebar" => [], "footer" => [], "main" => []];
?>
<!DOCTYPE html>
<html>
<head>
  <title>DnD Widget Layout</title>
  <style>
    .zone { border: 1px solid #ccc; padding: 10px; margin: 10px; min-height: 50px; }
    .widget { background: #eee; margin: 5px; padding: 5px; cursor: grab; }
  </style>
</head>
<body>
<h2>Sidebar</h2>
<div id="sidebar" class="zone">
  <?php foreach($layout['sidebar'] as $w): ?>
    <div class="widget" data-widget="<?= $w ?>"><?= ucfirst($w) ?></div>
  <?php endforeach; ?>
</div>

<h2>Main</h2>
<div id="main" class="zone">
  <?php foreach($layout['main'] as $w): ?>
    <div class="widget" data-widget="<?= $w ?>"><?= ucfirst($w) ?></div>
  <?php endforeach; ?>
</div>

<h2>Footer</h2>
<div id="footer" class="zone">
  <?php foreach($layout['footer'] as $w): ?>
    <div class="widget" data-widget="<?= $w ?>"><?= ucfirst($w) ?></div>
  <?php endforeach; ?>
</div>

<button id="save">Save Layout</button>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="script.js"></script>
</body>
</html>
