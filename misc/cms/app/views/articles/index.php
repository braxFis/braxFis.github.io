<?php
require_once __DIR__ . '/styles.php';
$articleEscape = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<section class="coma-editorial" aria-labelledby="editorial-title">
    <header class="ed-heading">
        <div><span class="ed-eyebrow">COMA NEWS · REDAKTIONEN</span><h1 id="editorial-title" class="ed-title">Artiklar</h1></div>
        <span class="ed-count"><?= count($all) ?> artiklar</span>
    </header>
    <?php if (!$articles): ?><p class="ed-empty">Här samlas våra artiklar. Det finns inga publicerade artiklar att visa ännu.</p><?php endif; ?>
    <?php foreach ($articles as $index => $article): ?>
        <article class="ed-row<?= $article['image'] !== '' ? ' has-image' : '' ?>">
            <span class="ed-number" aria-hidden="true"><?= str_pad((string) (($page - 1) * 20 + $index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <?php if ($article['image'] !== ''): ?>
                <a class="ed-media" href="/articles/<?= (int) $article['id'] ?>" tabindex="-1" aria-hidden="true"><img src="<?= $articleEscape($article['image']) ?>" alt="" loading="lazy"></a>
            <?php endif; ?>
            <div class="ed-copy">
                <p class="ed-category"><?= $articleEscape(implode(' / ', $article['categories']) ?: 'Artikel') ?></p>
                <h2 class="ed-row-title"><a href="/articles/<?= (int) $article['id'] ?>"><?= $articleEscape($article['title']) ?></a></h2>
                <p class="ed-excerpt"><?= $articleEscape(html_entity_decode($article['excerpt'], ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?></p>
                <div class="ed-row-bottom">
                    <div class="ed-meta"><span><?= $articleEscape($article['author']) ?></span><time datetime="<?= $articleEscape($article['published_at']) ?>"><?= $articleEscape(substr($article['published_at'], 0, 10)) ?></time></div>
                    <a class="ed-link" href="/articles/<?= (int) $article['id'] ?>" aria-label="<?= $articleEscape('Läs ' . $article['title']) ?>">Läs artikeln →</a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
    <?php if ($pages > 1): ?>
    <nav class="ed-pagination" aria-label="Artikelsidor">
        <span><?php if ($page > 1): ?><a class="ed-link" href="/articles?page=<?= $page - 1 ?>">← Föregående</a><?php endif; ?></span>
        <span class="ed-page-number">Sida <?= $page ?> av <?= $pages ?></span>
        <span><?php if ($page < $pages): ?><a class="ed-link" href="/articles?page=<?= $page + 1 ?>">Nästa →</a><?php endif; ?></span>
    </nav>
    <?php endif; ?>
</section>
