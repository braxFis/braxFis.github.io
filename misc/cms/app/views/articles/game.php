<?php if (!empty($relatedArticles)):
require_once __DIR__ . '/styles.php';
$articleEscape = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); ?>
<section class="coma-editorial ed-related" aria-labelledby="game-editorial-title">
    <header class="ed-heading">
        <div><span class="ed-eyebrow">FRÅN REDAKTIONEN</span><h2 id="game-editorial-title" class="ed-title">Artiklar om spelet</h2></div>
        <a class="ed-link" href="/articles">Alla artiklar →</a>
    </header>
    <div class="ed-related-grid">
    <?php foreach (array_slice($relatedArticles, 0, 20) as $relatedArticle): ?>
        <article class="ed-related-card">
            <?php if ($relatedArticle['image'] !== ''): ?><a class="ed-media" href="/articles/<?= (int) $relatedArticle['id'] ?>" tabindex="-1" aria-hidden="true"><img src="<?= $articleEscape($relatedArticle['image']) ?>" alt="" loading="lazy"></a><?php endif; ?>
            <p class="ed-category"><?= $articleEscape(implode(' / ', $relatedArticle['categories']) ?: 'Artikel') ?></p>
            <h3 class="ed-row-title"><a href="/articles/<?= (int) $relatedArticle['id'] ?>"><?= $articleEscape($relatedArticle['title']) ?></a></h3>
            <p class="ed-excerpt"><?= $articleEscape(html_entity_decode($relatedArticle['excerpt'], ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?></p>
            <div class="ed-row-bottom">
                <div class="ed-meta"><span><?= $articleEscape($relatedArticle['author']) ?></span><time datetime="<?= $articleEscape($relatedArticle['published_at']) ?>"><?= $articleEscape(substr($relatedArticle['published_at'], 0, 10)) ?></time></div>
                <a class="ed-link" href="/articles/<?= (int) $relatedArticle['id'] ?>" aria-label="<?= $articleEscape('Läs ' . $relatedArticle['title']) ?>">Läs mer →</a>
            </div>
        </article>
    <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
