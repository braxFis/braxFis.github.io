<?php if (!empty($relatedArticles)):
$articleEscape = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); ?>
<section aria-label="Redaktionella artiklar">
    <h2>Artiklar om spelet</h2>
    <?php foreach (array_slice($relatedArticles, 0, 20) as $relatedArticle): ?>
        <article>
            <h3><a href="/articles/<?= (int) $relatedArticle['id'] ?>"><?= $articleEscape($relatedArticle['title']) ?></a></h3>
            <p><?= $articleEscape($relatedArticle['excerpt']) ?></p>
        </article>
    <?php endforeach; ?>
    <p><a href="/articles">Alla artiklar</a></p>
</section>
<?php endif; ?>
