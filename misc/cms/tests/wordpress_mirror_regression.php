<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/services/ArticleHtml.php';
require dirname(__DIR__) . '/app/services/WordPressMirror.php';

use app\services\ArticleHtml;
use app\services\WordPressMirror;

function check(bool $ok, string $message): void {
    if (!$ok) { throw new RuntimeException($message); }
}
function fails(callable $fn, string $message): void {
    try { $fn(); } catch (Throwable $error) { return; }
    throw new RuntimeException($message);
}
function article(int $id, string $source = 'igdb'): array {
    return ['id' => $id, 'status' => 'publish', 'title' => 'Spel & nyheter ' . $id,
        'content_html' => '<h2>Nyhet</h2><p>Text <strong>med formatering</strong>.</p>',
        'excerpt' => 'Sammanfattning', 'url' => 'https://wp.example/articles/' . $id,
        'published_at' => '2026-11-15T12:00:00+01:00', 'modified_at' => '2026-11-15T12:00:00+01:00',
        'author' => 'Redaktionen', 'image' => 'https://wp.example/image.jpg',
        'categories' => ['Nyheter'], 'tags' => ['PC'],
        'game' => ['source' => $source, 'id' => 42]];
}
function feed(array $items, string $generation = ''): callable {
    $generation = $generation ?: str_repeat('a', 64);
    return static function (string $url) use ($items, $generation): array {
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $page = (int) ($query['page'] ?? 1);
        return ['schema' => 1, 'source' => 'https://wp.example', 'generation' => $generation,
            'page' => $page, 'pages' => max(1, (int) ceil(count($items) / 50)),
            'total' => count($items), 'articles' => array_slice($items, ($page - 1) * 50, 50)];
    };
}
$dir = sys_get_temp_dir() . '/coma-mirror-' . bin2hex(random_bytes(6));
mkdir($dir);
$file = $dir . '/articles.json';
try {
    $items = array_map('article', range(1, 51));
    $mirror = new WordPressMirror('https://wp.example', $file, feed($items));
    $preview = $mirror->sync();
    check($preview['added'] === 51 && !is_file($file), 'Dry run must not publish articles');
    $mirror->sync(true);
    check(count($mirror->articles()) === 51, 'All export pages imported');
    check(count($mirror->forGame('igdb-42')) === 51, 'IGDB game links match');
    check($mirror->forGame('42') === [], 'Never mix RAWG and IGDB IDs');
    check($mirror->sync(true)['added'] === 0, 'Repeated sync cannot duplicate articles');
    $original = file_get_contents($file);

    $broken = new WordPressMirror('https://wp.example', $file, static function ($url) use ($items) {
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        if (($query['page'] ?? 1) == 2) { throw new RuntimeException('Timeout'); }
        return feed($items)($url);
    });
    fails(fn() => $broken->sync(true), 'Partial export must fail');
    check(file_get_contents($file) === $original, 'Failed sync must preserve previous snapshot');

    $changed = new WordPressMirror('https://wp.example', $file, static function ($url) use ($items) {
        $data = feed($items)($url);
        if ($data['page'] === 2) { $data['generation'] = str_repeat('b', 64); }
        return $data;
    });
    fails(fn() => $changed->sync(true), 'Concurrent edits must abort');
    check(file_get_contents($file) === $original, 'Concurrent edits must preserve snapshot');
    $duplicate = $items;
    $duplicate[50] = $duplicate[0];
    fails(fn() => (new WordPressMirror('https://wp.example', $file, feed($duplicate)))->sync(true), 'Reject duplicate IDs');
    $private = [article(1)]; $private[0]['status'] = 'draft';
    fails(fn() => (new WordPressMirror('https://wp.example', $file, feed($private)))->sync(true), 'Drafts must never import');

    $updated = article(1); $updated['title'] = 'Uppdaterad';
    $result = (new WordPressMirror('https://wp.example', $file, feed([$updated])))->sync(true);
    check($result['updated'] === 1 && $result['removed'] === 50, 'Updates and withdrawals reconcile');
    check(count($mirror->articles()) === 1, 'Unpublished entries disappear');
    $rawg = new WordPressMirror('https://wp.example', $file, feed([article(1, 'rawg')]));
    $rawg->sync(true);
    check(count($rawg->forGame('42')) === 1 && $rawg->forGame('igdb-42') === [], 'RAWG IDs stay separate');
    (new WordPressMirror('https://wp.example', $file, feed([])))->sync(true);
    check($mirror->articles() === [], 'Valid empty export removes all mirrored articles');

    $clean = ArticleHtml::clean('<p onclick="evil()">Hej<script>alert(1)</script><a href="javascript:evil()">x</a><img src="https://wp.example/x.jpg" onerror="evil()"><iframe src="https://evil.example"></iframe><svg onload="evil()"></svg></p>');
    check(str_contains($clean, 'Hej') && str_contains($clean, 'https://wp.example/x.jpg'), 'Safe editorial content survives');
    check(!preg_match('/onclick|onerror|onload|javascript:|<script|<iframe|<svg/i', $clean), 'Executable content removed');
    check(ArticleHtml::url('data:text/html,evil') === '', 'Reject unsafe URL schemes');
    check(ArticleHtml::url("https://wp.example/\nfoo") === '', 'Reject control characters');
    fails(fn() => new WordPressMirror('https://user:secret@wp.example', $file), 'Reject URL credentials');
    echo "WordPress mirror regression tests passed.\n";
} finally {
    foreach (glob($dir . '/*') as $path) { unlink($path); }
    rmdir($dir);
}
