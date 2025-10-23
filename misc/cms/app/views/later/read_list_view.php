<?php
// $readlist finns från controllern
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Mina sparade artiklar</title></head>
<body>
<h1>📘 Mina sparade artiklar</h1>

<?php if (empty($readlist)): ?>
  <p>Du har inga sparade artiklar.</p>
<?php else: ?>
  <ul>
    <?php foreach ($readlist as $article): ?>
      <li>
        <a href="/news/indie/<?= htmlspecialchars($article['id']) ?>">
          <?= htmlspecialchars($article['title']) ?>
        </a>
        <small>— sparad <?= htmlspecialchars($article['created_at'] ?? '') ?></small>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
</body>
</html>
