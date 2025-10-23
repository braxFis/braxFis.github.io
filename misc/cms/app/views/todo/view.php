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
    <?php foreach ($todos as $todo): ?>
      <li>
        <a href="/news/indie/<?= htmlspecialchars($todo['id']) ?>">
          <?= htmlspecialchars($todo['name']) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
</body>
</html>
