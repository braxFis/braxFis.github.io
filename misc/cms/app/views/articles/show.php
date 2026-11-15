<?php
$articleEscape = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<article class="editorial-article">
    <p><a href="/articles">Alla artiklar</a></p>
    <h1><?= $articleEscape($article['title']) ?></h1>
    <p><?= $articleEscape($article['author']) ?> · <?= $articleEscape(substr($article['published_at'], 0, 10)) ?></p>
    <?php if ($article['image'] !== ''): ?>
        <img src="<?= $articleEscape($article['image']) ?>" alt="" style="max-width:100%;height:auto">
    <?php endif; ?>
    <div class="editorial-article-body">
        <?= \app\services\ArticleHtml::clean($article['content_html']) ?>
    </div>
    <?php if ($article['game'] !== null):
        $articleGameId = ($article['game']['source'] === 'igdb' ? 'igdb-' : '') . $article['game']['id']; ?>
        <p><a href="/games/<?= rawurlencode($articleGameId) ?>">Till spelet</a></p>
    <?php endif; ?>
    <p><a href="<?= $articleEscape($article['url']) ?>" rel="noopener noreferrer">Original på WordPress</a></p>
</article>
<style>.editorial-article-body img{max-width:100%;height:auto}.editorial-article-body table{display:block;overflow:auto}</style>
