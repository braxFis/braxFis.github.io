<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
if (in_array('--help', $argv, true)) {
    echo "Usage: php bin/sync-wordpress.php [--apply]\nWithout --apply: preview only; no articles are changed.\n";
    exit(0);
}
try {
    $root = dirname(__DIR__);
    require $root . '/vendor/autoload.php';
    foreach ([$root . '/config', $root] as $directory) {
        \Dotenv\Dotenv::createUnsafeImmutable($directory)->safeLoad();
    }
    $mirror = \app\services\WordPressMirror::configured();
    if (!$mirror) { throw new RuntimeException('Set WORDPRESS_URL before syncing.'); }
    foreach (array_slice($argv, 1) as $argument) {
        if ($argument !== '--apply') { throw new InvalidArgumentException('Unknown argument. Use --help.'); }
    }
    $result = $mirror->sync(in_array('--apply', $argv, true));
    echo json_encode($result, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE) . "\n";
} catch (Throwable $error) {
    fwrite(STDERR, 'WordPress sync failed: ' . $error->getMessage() . "\n");
    exit(1);
}
