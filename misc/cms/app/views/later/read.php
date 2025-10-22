<ul>
<?php foreach ($news as $new): ?>
  <li>
    <?= htmlspecialchars($new->title) ?>
    <a href="?add=<?= $new->id ?>">📘 Läs senare</a>
  </li>
<?php endforeach; ?>
</ul>