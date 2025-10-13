<div class="tabs">
  <div class="tab-item <?= ($tab === 'tba') ? 'active' : '' ?>">
    <a href="/sidebar?tab=tba">TBA</a>
  </div>
  <div class="tab-item <?= ($tab === 'metacritic') ? 'active' : '' ?>">
    <a href="/sidebar?tab=metacritic">Top Metacritic</a>
  </div>
</div>

<div class="tab-panels">
  <div id="tba" class="tab-panel" style="<?= $tab === 'tba' ? '' : 'display:none;' ?>">
    <?php if ($tab === 'tba'): ?>
      <h2>Upcoming Games</h2>
      <div class="games">
        <?php foreach ($games as $game): ?>
          <div class="game">
            <?php if (!empty($game['background_image'])): ?>
              <img src="<?= htmlspecialchars($game['background_image']) ?>" width="200">
            <?php endif; ?>
            <h6><?= htmlspecialchars($game['name']) ?></h6>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <div id="metacritic" class="tab-panel" style="<?= $tab === 'metacritic' ? '' : 'display:none;' ?>">
    <?php if ($tab === 'metacritic'): ?>
      <h2>Top Rated by Metacritic</h2>
      <div class="games">
        <?php foreach ($games as $game): ?>
          <div class="game">
            <?php if (!empty($game['background_image'])): ?>
              <img src="<?= htmlspecialchars($game['background_image']) ?>" width="200">
            <?php endif; ?>
            <h6>
              <?= htmlspecialchars($game['name']) ?>
              (<?= htmlspecialchars($game['metacritic'] ?? 'N/A') ?>)
            </h6>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
