<?php $articleEscape = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); ?>
<section class="editorial-articles">
    <h1>Artiklar</h1>
    <?php if (!$articles): ?><p>Inga publicerade artiklar har synkroniserats ännu.</p><?php endif; ?>
    <?php foreach ($articles as $article): ?>
        <article>
            <h2><a href="/articles/<?= (int) $article['id'] ?>"><?= $articleEscape($article['title']) ?></a></h2>
            <p><?= $articleEscape($article['author']) ?> · <?= $articleEscape(substr($article['published_at'], 0, 10)) ?></p>
            <?php if ($article['image'] !== ''): ?>
                <img src="<?= $articleEscape($article['image']) ?>" alt="" loading="lazy" style="max-width:100%;height:auto">
            <?php endif; ?>
            <p><?= $articleEscape($article['excerpt']) ?></p>
        </article>
    <?php endforeach; ?>
    <nav aria-label="Artikelsidor">
        <?php if ($page > 1): ?><a href="/articles?page=<?= $page - 1 ?>">Föregående</a><?php endif; ?>
        <?php if ($page < $pages): ?><a href="/articles?page=<?= $page + 1 ?>">Nästa</a><?php endif; ?>
    </nav>
</section>
