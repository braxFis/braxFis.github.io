<?php
require_once __DIR__ . '/styles.php';
$articleEscape = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<article class="coma-editorial" aria-labelledby="editorial-article-title">
    <nav class="ed-back" aria-label="Tillbaka"><a class="ed-link" href="/articles">← Alla artiklar</a></nav>
    <header class="ed-hero">
        <span class="ed-eyebrow">COMA NEWS · <?= $articleEscape(implode(' / ', $article['categories']) ?: 'REDAKTIONEN') ?></span>
        <h1 class="ed-title" id="editorial-article-title"><?= $articleEscape($article['title']) ?></h1>
        <div class="ed-meta"><span>Av <?= $articleEscape($article['author']) ?></span><time datetime="<?= $articleEscape($article['published_at']) ?>"><?= $articleEscape(substr($article['published_at'], 0, 10)) ?></time></div>
    </header>
    <?php if ($article['image'] !== ''): ?>
        <figure class="ed-cover"><img src="<?= $articleEscape($article['image']) ?>" alt=""></figure>
    <?php endif; ?>
    <div class="ed-reading-layout">
        <div class="ed-body"><?= \app\services\ArticleHtml::clean($article['content_html']) ?></div>
        <aside class="ed-aside" aria-label="Om artikeln">
            <div class="ed-fact"><span class="ed-fact-label">Skribent</span><p><?= $articleEscape($article['author']) ?></p></div>
            <?php if ($article['categories']): ?><div class="ed-fact"><span class="ed-fact-label">Kategori</span><p><?= $articleEscape(implode(', ', $article['categories'])) ?></p></div><?php endif; ?>
            <?php if ($article['tags']): ?><div class="ed-fact"><span class="ed-fact-label">Ämnen</span><p><?= $articleEscape(implode(', ', $article['tags'])) ?></p></div><?php endif; ?>
            <?php if ($article['game'] !== null):
                $articleGameId = ($article['game']['source'] === 'igdb' ? 'igdb-' : '') . $article['game']['id']; ?>
                <div class="ed-fact"><span class="ed-fact-label">Utforska vidare</span><a class="ed-link" href="/games/<?= rawurlencode($articleGameId) ?>">Till spelsidan →</a></div>
            <?php endif; ?>
            <div class="ed-fact"><a class="ed-link" href="<?= $articleEscape($article['url']) ?>" rel="noopener noreferrer">Original på WordPress ↗</a></div>
        </aside>
    </div>
</article>
