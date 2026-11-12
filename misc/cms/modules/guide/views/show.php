<article class="walkthrough">
    <p><a href="/games/<?= (int) $id ?>/guides">All walkthroughs for this game</a></p>
    <h1><?= htmlspecialchars((string) $guide['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
    <?php foreach (($guide['sections'] ?? []) as $section): ?>
        <section>
            <h2><?= htmlspecialchars((string) ($section['heading'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h2>
            <p><?= nl2br(htmlspecialchars((string) ($section['text'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?></p>
        </section>
    <?php endforeach; ?>
</article>
