<div class="page-view-container">
<h1><?= $page->title;?></h1>

<?php foreach ($layout as $block): ?>
  <?php
    $type = $block['type'] ?? '';
    $content = $block['content'] ?? '';

    switch ($type) {
      case 'h1':
        echo "<h1>" . htmlspecialchars($content) . "</h1>";
        break;
      case 'h2':
        echo "<h2>" . htmlspecialchars($content) . "</h2>";
        break;
      case 'p':
        echo "<p>" . nl2br(htmlspecialchars($content)) . "</p>";
        break;
      case 'img':
        echo "<img src='" . htmlspecialchars($content) . "' alt='Bild'>";
        break;
      case 'textarea':
        echo "<div class='textarea-block'>" . nl2br(htmlspecialchars($content)) . "</div>";
        break;
      case 'a':
        echo "<a href='" . htmlspecialchars($content) . "'>" . htmlspecialchars($content) . "</a>";
        break;
      case 'audio':
        echo "<audio controls src='" . htmlspecialchars($content) . "'></audio>";
        break;
      case 'video':
        echo "<video controls width='250'><source src='" . htmlspecialchars($content) . "' type='video/mp4'>Din webbläsare stödjer inte videouppspelning.</video>";
        break;
      case 'i':
        echo "<i>" . htmlspecialchars($content) . "</i>";
        break;
      default:
        echo "<div class='unknown-block'>Okänd typ: $type</div>";
    }
  ?>
<?php endforeach; ?>
</div>
<style>
.page-view-container {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
}
</style>