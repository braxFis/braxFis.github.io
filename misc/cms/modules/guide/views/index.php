<section class="walkthroughs">
    <h1>Walkthroughs</h1>
    <p><a href="/games/<?= (int) $id ?>">Back to game</a></p>
    <?php if (empty($guides)): ?>
        <p>No guides are available for this game yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($guides as $item): ?>
                <li><a href="/games/<?= (int) $id ?>/guides/<?= rawurlencode((string) $item['slug']) ?>"><?= htmlspecialchars((string) $item['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
