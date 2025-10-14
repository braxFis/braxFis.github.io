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
      default:
        echo "<div class='unknown-block'>Okänd typ: $type</div>";
    }
  ?>
<?php endforeach; ?>
