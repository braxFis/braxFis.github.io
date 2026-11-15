<?php
declare(strict_types=1);
namespace app\services;

/** One WordPress source, one atomically replaced local snapshot. No HTTP on page views. */
final class WordPressMirror
{
    private const MAX_BYTES = 33554432;
    private string $source;
    private string $file;
    private $fetch;

    public function __construct(string $source, string $file, ?callable $fetch = null)
    {
        $this->source = rtrim($source, '/');
        if (ArticleHtml::url($this->source) !== $this->source
            || parse_url($this->source, PHP_URL_QUERY) !== null
            || parse_url($this->source, PHP_URL_FRAGMENT) !== null) {
            throw new \InvalidArgumentException('WORDPRESS_URL must be an absolute site URL without a query.');
        }
        if (parse_url($this->source, PHP_URL_SCHEME) !== 'https' && getenv('WORDPRESS_ALLOW_HTTP') !== '1') {
            throw new \InvalidArgumentException('Use HTTPS, or explicitly allow HTTP for local development.');
        }
        $this->file = $file;
        $this->fetch = $fetch ?? [$this, 'http'];
    }

    public static function configured(): ?self
    {
        $source = trim((string) getenv('WORDPRESS_URL'));
        if ($source === '') { return null; }
        $base = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
        $file = getenv('WORDPRESS_MIRROR_FILE') ?: $base . '/var/wordpress/articles.json';
        // Never expose the raw snapshot as a static public file.
        $public = realpath($base . '/public');
        $directory = realpath(dirname($file));
        if ($public && $directory && ($directory === $public || str_starts_with($directory, $public . DIRECTORY_SEPARATOR))) {
            throw new \InvalidArgumentException('Mirror file must be outside the public directory.');
        }
        return new self($source, $file);
    }

    public function articles(): array
    {
        if (!is_file($this->file)) { return []; }
        if (filesize($this->file) > self::MAX_BYTES) { throw new \RuntimeException('Mirror exceeds size limit.'); }
        $snapshot = json_decode((string) file_get_contents($this->file), true, 128, JSON_THROW_ON_ERROR);
        if (($snapshot['schema'] ?? null) !== 1 || ($snapshot['source'] ?? '') !== $this->source
            || !is_array($snapshot['articles'] ?? null)) {
            throw new \RuntimeException('Mirror source or schema does not match configuration.');
        }
        return $snapshot['articles'];
    }

    public function forGame(string $id): array
    {
        if (!preg_match('/^(igdb-)?([1-9][0-9]*)$/', $id, $match)) { return []; }
        $source = !empty($match[1]) ? 'igdb' : 'rawg';
        return array_values(array_filter($this->articles(), static fn($a) =>
            ($a['game']['source'] ?? '') === $source && (string) ($a['game']['id'] ?? '') === $match[2]));
    }

    public function sync(bool $apply = false): array
    {
        $directory = dirname($this->file);
        if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
            throw new \RuntimeException('Cannot create mirror directory.');
        }
        $lock = fopen($this->file . '.lock', 'c');
        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            if ($lock) { fclose($lock); }
            throw new \RuntimeException('Another sync is running.');
        }
        try {
            $items = [];
            $generation = null;
            $pages = null;
            $total = null;
            $bytes = 0;
            for ($page = 1; ; $page++) {
                $params = ['page' => $page];
                if ($generation !== null) { $params['generation'] = $generation; }
                $url = $this->source . '/wp-json/coma-sync/v1/articles?' . http_build_query($params);
                $data = ($this->fetch)($url);
                if (!is_array($data) || ($data['schema'] ?? null) !== 1
                    || ($data['source'] ?? '') !== $this->source
                    || ($data['page'] ?? null) !== $page
                    || !is_int($data['pages'] ?? null) || $data['pages'] < 1 || $data['pages'] > 200
                    || !is_int($data['total'] ?? null) || $data['total'] < 0 || $data['total'] > 10000
                    || !is_string($data['generation'] ?? null) || !preg_match('/^[a-f0-9]{64}$/', $data['generation'])
                    || !is_array($data['articles'] ?? null) || !array_is_list($data['articles'])) {
                    throw new \RuntimeException('Invalid WordPress export; previous mirror kept.');
                }
                if ($generation === null) {
                    $generation = $data['generation']; $pages = $data['pages']; $total = $data['total'];
                }
                if ($generation !== $data['generation'] || $pages !== $data['pages'] || $total !== $data['total']) {
                    throw new \RuntimeException('WordPress changed during pagination; retry sync.');
                }
                foreach ($data['articles'] as $item) {
                    $article = $this->normalize($item);
                    if (isset($items[$article['id']])) { throw new \RuntimeException('Duplicate WordPress ID.'); }
                    $bytes += strlen(json_encode($article, JSON_THROW_ON_ERROR));
                    if ($bytes > self::MAX_BYTES - 1024) { throw new \RuntimeException('Export exceeds size limit.'); }
                    $items[$article['id']] = $article;
                }
                if ($page >= $pages) { break; }
            }
            if (count($items) !== $total) { throw new \RuntimeException('Incomplete export; previous mirror kept.'); }
            // Check generation again after the last page, including empty exports.
            $final = ($this->fetch)($this->source . '/wp-json/coma-sync/v1/articles?' .
                http_build_query(['page' => 1, 'generation' => $generation]));
            if (($final['generation'] ?? null) !== $generation || ($final['total'] ?? null) !== $total
                || ($final['source'] ?? '') !== $this->source) {
                throw new \RuntimeException('WordPress changed before commit; retry sync.');
            }
            $old = $this->articles();
            $oldById = array_column($old, null, 'id');
            $added = $updated = 0;
            foreach ($items as $id => $item) {
                if (!isset($oldById[$id])) { $added++; }
                elseif ($oldById[$id] !== $item) { $updated++; }
            }
            $removed = count(array_diff_key($oldById, $items));
            $list = array_values($items);
            usort($list, static fn($a, $b) => strcmp($b['published_at'], $a['published_at']) ?: $b['id'] <=> $a['id']);
            if ($apply) {
                $json = json_encode(['schema' => 1, 'source' => $this->source,
                    'synced_at' => gmdate('c'), 'articles' => $list], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
                $temp = tempnam($directory, '.articles-');
                if ($temp === false) { throw new \RuntimeException('Cannot create snapshot.'); }
                try {
                    if (file_put_contents($temp, $json) !== strlen($json) || !rename($temp, $this->file)) {
                        throw new \RuntimeException('Cannot publish snapshot.');
                    }
                } finally {
                    if (is_file($temp)) { unlink($temp); }
                }
            }
            return compact('added', 'updated', 'removed', 'total', 'apply');
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    private function normalize($item): array
    {
        if (!is_array($item) || !is_int($item['id'] ?? null) || $item['id'] < 1
            || ($item['status'] ?? '') !== 'publish') {
            throw new \RuntimeException('Export contains a non-public or invalid article.');
        }
        foreach (['title','content_html','excerpt','url','published_at','modified_at','author','image'] as $key) {
            if (!is_string($item[$key] ?? null)) { throw new \RuntimeException('Missing article field: ' . $key); }
        }
        foreach (['published_at','modified_at'] as $key) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:Z|[+-]\d{2}:\d{2})$/', $item[$key])
                || strtotime($item[$key]) === false) { throw new \RuntimeException('Invalid article date.'); }
            $item[$key] = gmdate('c', strtotime($item[$key]));
        }
        $game = $item['game'] ?? null;
        if ($game !== null && (!is_array($game) || !in_array($game['source'] ?? '', ['igdb','rawg'], true)
            || !is_int($game['id'] ?? null) || $game['id'] < 1)) {
            throw new \RuntimeException('Invalid game mapping.');
        }
        $terms = [];
        foreach (['categories','tags'] as $key) {
            if (!is_array($item[$key] ?? null)) { throw new \RuntimeException('Invalid article taxonomy.'); }
            $terms[$key] = [];
            foreach ($item[$key] as $term) {
                if (!is_string($term)) { throw new \RuntimeException('Invalid taxonomy name.'); }
                $terms[$key][] = strip_tags($term);
            }
        }
        $url = ArticleHtml::url($item['url']);
        if ($url === '') { throw new \RuntimeException('Invalid article URL.'); }
        return [
            'id' => $item['id'], 'title' => strip_tags($item['title']),
            'content_html' => ArticleHtml::clean($item['content_html']),
            'excerpt' => strip_tags($item['excerpt']), 'url' => $url,
            'published_at' => $item['published_at'], 'modified_at' => $item['modified_at'],
            'author' => strip_tags($item['author']), 'image' => ArticleHtml::url($item['image']),
            'categories' => $terms['categories'], 'tags' => $terms['tags'],
            'game' => $game === null ? null : ['source' => $game['source'], 'id' => $game['id']],
        ];
    }

    private function http(string $url): array
    {
        $body = '';
        $ch = curl_init($url);
        if ($ch === false) { throw new \RuntimeException('Cannot start WordPress request.'); }
        curl_setopt_array($ch, [
            CURLOPT_CONNECTTIMEOUT => 5, CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => false, CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'Cache-Control: no-cache'],
            CURLOPT_WRITEFUNCTION => static function ($handle, string $chunk) use (&$body): int {
                if (strlen($body) + strlen($chunk) > 4194304) { return 0; }
                $body .= $chunk;
                return strlen($chunk);
            },
        ]);
        $ok = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($ok === false || $status !== 200) {
            throw new \RuntimeException('WordPress export HTTP ' . $status . '; previous mirror kept.');
        }
        $data = json_decode($body, true, 128, JSON_THROW_ON_ERROR);
        if (!is_array($data)) { throw new \RuntimeException('WordPress returned invalid JSON.'); }
        return $data;
    }
}
