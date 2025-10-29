<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Games</title>
<link rel="stylesheet" href="/misc/css/style.css">
</head>
<body>

<h1>Games</h1>

<?php foreach ($games as $item): ?>
<div class="game">
    <h3><?= htmlspecialchars($item['name']) ?></h3>
    <p><strong>Release Date:</strong> <?= htmlspecialchars($item['released']) ?></p>
    <img src="<?= htmlspecialchars($item['background_image']) ?>" width="200" height="200"/>

    <p><?= $item['description'] ?></p>
    <p><strong>Rating:</strong> <?= htmlspecialchars($item['rating']) ?></p>
    <p><strong>Metacritic:</strong> <?= htmlspecialchars($item['metacritic']) ?></p>

    <p><strong>Platforms:</strong>
        <?= implode(", ", array_map(fn($p) => $p['platform']['name'], $item['platforms'])) ?>
    </p>

    <p><strong>Genre(s):</strong>
        <?= implode(", ", array_map(fn($g) => $g['name'], $item['genres'])) ?>
    </p>

    <p><strong>Store availability:</strong></p>
    <?php foreach ($item['stores'] as $store): ?>
        <a href="https://<?= htmlspecialchars($store['store']['domain']) ?>" target="_blank">
            <?= htmlspecialchars($store['store']['name']) ?>
        </a>
    <?php endforeach; ?>

    <p><strong>ESRB Rating:</strong> <?= $item['esrb_rating']['name'] ?? 'Not Rated' ?></p>
</div>
<hr>
<?php endforeach; ?>

<button id="loadMoreBtn" data-page="1">Load More</button>

<?php

use app\widgets\ReviewWidget;
echo ReviewWidget::renderTop100();

?>

<script>

document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("loadMoreBtn");
    const container = document.getElementById("game-container");

    btn.addEventListener("click", async () => {
        let currentPage = parseInt(btn.dataset.page);
        let nextPage = currentPage + 1;

        const response = await fetch(`/index/loadMore?page=${nextPage}`);
        const games = await response.json();

        if (!games || games.length === 0) {
            btn.disabled = true;
            btn.textContent = "No more games";
            return;
        }

        games.forEach(game => {
            const block = document.createElement("div");
            block.className = "game";
            block.innerHTML = `
                <h3>${game.name}</h3>
                ${game.background_image ? `<img src="${game.background_image}" width="200">` : ''}
                <p>Released: ${game.released}</p>
            `;
            container.appendChild(block);
        });

        btn.dataset.page = nextPage;
    });
});

</script>
</body>
</html>
