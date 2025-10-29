<section id="search-section">

  <?php if (!empty($results)): ?>
    <div class="search-results">
      <h1>Search Results</h1>

      <?php foreach ($results as $item): ?>
        <div class="game-container">
          <h3><?= htmlspecialchars($item['name']) ?></h3>
          <p><strong>Release Date:</strong> <?= htmlspecialchars($item['released']) ?></p>
          <?php if ($item['image']): ?>
            <img src="<?= htmlspecialchars($item['image']) ?>" width="200" height="200">
          <?php endif; ?>
          <p><?= $item['description'] ?></p>
          <p><strong>Rating:</strong> <?= htmlspecialchars($item['rating']) ?></p>
          <p><strong>Metacritic:</strong> <?= htmlspecialchars($item['metacritic']) ?></p>

          <?php if (!empty($item['platforms'])): ?>
            <p><strong>Platforms:</strong>
              <?= htmlspecialchars(implode(', ', array_column(array_column($item['platforms'], 'platform'), 'name'))) ?>
            </p>
          <?php endif; ?>

          <p><strong>Genre(s):</strong>
            <?= htmlspecialchars(implode(', ', array_column($item['genres'], 'name'))) ?>
          </p>

          <p><strong>ESRB Rating:</strong> <?= htmlspecialchars($item['esrb']) ?></p>

          <?php if (!empty($item['screenshots'])): ?>
            <p><strong>Screenshots</strong></p>
            <?php foreach ($item['screenshots'] as $s): ?>
              <a href="<?= htmlspecialchars($s['image']) ?>" target="_blank">
                <img src="<?= htmlspecialchars($s['image']) ?>" width="200" height="200">
              </a>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!empty($item['trailers'])): ?>
            <p><strong>Trailers</strong></p>
            <?php foreach ($item['trailers'] as $trailer): ?>
              <video width="320" height="240" controls>
                <source src="<?= htmlspecialchars($trailer) ?>" type="video/mp4">
              </video>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php elseif (!empty($_GET['q'])): ?>
    <p>Inga resultat hittades för "<?= htmlspecialchars($_GET['q']) ?>"</p>
  <?php endif; ?>
</section>
