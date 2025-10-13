<h1>🎮 Game Trailers</h1>

<?php foreach ($gamesWithTrailers as $game): ?>
  <div class="game-block">
    <h2><?= htmlspecialchars($game['name']) ?></h2>
    <div class="genres">Genres: <?= htmlspecialchars(implode(', ', $game['genres'])) ?></div>

    <?php foreach ($game['trailers'] as $trailer): ?>
      <div class="trailer">
        <h4><?= htmlspecialchars($trailer['name']) ?></h4>
        <video width="640" height="320" controls poster="<?= htmlspecialchars($trailer['preview']) ?>">
          <source src="<?= htmlspecialchars($trailer['data']['max']) ?>" type="video/mp4">
        </video>
      </div>
    <?php endforeach; ?>
  </div>
<?php endforeach; ?>

<style>
  .game-block { margin-bottom: 40px; padding: 10px; border-bottom: 1px solid #333; }
  .trailer { margin-top: 15px; }
  .genres { font-style: italic; margin-bottom: 5px; }
</style>
