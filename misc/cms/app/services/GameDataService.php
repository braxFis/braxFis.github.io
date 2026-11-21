<?php

declare(strict_types=1);

namespace app\services;

/** Routes requests to RAWG or IGDB; provider IDs are never interchangeable. */
class GameDataService
{
    /**
     * RAWG genres and IGDB genres do not always mean the same thing.
     * Values below name IGDB's entity *names*, never hardcoded IGDB IDs.
     * "Action" and "Educational" are IGDB themes rather than genres.
     * Categories without a defensible equivalence are deliberately omitted.
     */
    private const IGDB_GENRE_FILTERS = [
        'action' => ['type' => 'themes', 'name' => 'Action'],
        'indie' => ['type' => 'genres', 'name' => 'Indie'],
        'adventure' => ['type' => 'genres', 'name' => 'Adventure'],
        'role-playing-games-rpg' => ['type' => 'genres', 'name' => 'Role-playing (RPG)'],
        'strategy' => ['type' => 'genres', 'name' => 'Strategy'],
        'shooter' => ['type' => 'genres', 'name' => 'Shooter'],
        'simulation' => ['type' => 'genres', 'name' => 'Simulator'],
        'puzzle' => ['type' => 'genres', 'name' => 'Puzzle'],
        'arcade' => ['type' => 'genres', 'name' => 'Arcade'],
        'platformer' => ['type' => 'genres', 'name' => 'Platform'],
        'massively-multiplayer' => ['type' => 'game_modes', 'name' => 'Massively Multiplayer Online (MMO)'],
        'racing' => ['type' => 'genres', 'name' => 'Racing'],
        'sports' => ['type' => 'genres', 'name' => 'Sport'],
        'fighting' => ['type' => 'genres', 'name' => 'Fighting'],
        'board-games' => ['type' => 'genres', 'name' => 'Card & Board Game'],
        'card' => ['type' => 'genres', 'name' => 'Card & Board Game'],
        'educational' => ['type' => 'themes', 'name' => 'Educational'],
        // No exact IGDB equivalents: casual, family.
    ];

    public function fetch(string $endpoint, array $params = []): ?array
    {
        if (preg_match('~^games/igdb-([0-9]+)(?:/(movies|screenshots))?$~', $endpoint, $matches)) {
            $id = (int) $matches[1];
            $type = $matches[2] ?? '';

            try {
                $client = new IgdbClient();

                if ($type === 'movies') {
                    $results = [];
                    foreach ($client->trailers($id) as $video) {
                        $videoId = $video['video_id'] ?? '';
                        if (!is_string($videoId) || !preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId)) {
                            continue;
                        }
                        $results[] = [
                            'name' => (string) ($video['name'] ?? 'Game Trailer'),
                            'preview' => 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg',
                            'data' => ['max' => $videoId],
                        ];
                    }
                    return ['results' => $results];
                }

                if ($type === 'screenshots') {
                    $results = [];
                    foreach ($client->screenshots($id) as $image) {
                        $imageId = $image['image_id'] ?? '';
                        if (!is_string($imageId) || !preg_match('/^[a-zA-Z0-9_-]+$/', $imageId)) {
                            continue;
                        }
                        $results[] = [
                            'image' => 'https://images.igdb.com/igdb/image/upload/t_screenshot_big/' . $imageId . '.jpg',
                        ];
                    }
                    return ['results' => $results];
                }

                return $client->game($id);
            } catch (\RuntimeException $e) {
                error_log('IGDB request failed: ' . $e->getMessage());
                return $type === 'movies' || $type === 'screenshots' ? ['results' => []] : null;
            }
        }

        try {
            $raw = $this->rawg($endpoint, $params);
            $adapter = new GameDataAdapter();
            if ($endpoint === 'games' && isset($raw['results']) && is_array($raw['results'])) {
                $raw['results'] = array_map([$adapter, 'fromRawg'], $raw['results']);
            } elseif (preg_match('~^games/[0-9]+$~', $endpoint) && !empty($raw)) {
                $raw = $adapter->fromRawg($raw);
            }
            return $raw;
        } catch (\RuntimeException $e) {
            error_log('RAWG request failed: ' . $e->getMessage());
            // Never ask IGDB for a numeric RAWG game ID.
            if ($endpoint !== 'games') {
                return null;
            }

            $igdbParams = $params;
            // The filter slug comes from Genre.php; IGDB entity IDs are resolved separately.
            if (isset($params['genre_slug']) || isset($params['genres'])) {
                $slug = (string) ($params['genre_slug'] ?? '');
                if ($slug === '' || !isset(self::IGDB_GENRE_FILTERS[$slug])) {
                    error_log('No safe IGDB genre equivalence for: ' . ($slug ?: 'missing genre_slug'));
                    return [
                        'results' => [],
                        'notice' => 'Denna RAWG-kategori har ingen verifierad motsvarighet i IGDB.',
                    ];
                }
                $igdbParams['igdb_filter'] = self::IGDB_GENRE_FILTERS[$slug];
                unset($igdbParams['genres'], $igdbParams['genre_slug']);
            }

            try {
                return (new IgdbClient())->games($igdbParams);
            } catch (\RuntimeException $inner) {
                error_log('IGDB fallback failed: ' . $inner->getMessage());
                return null;
            }
        }
    }

    private function rawg(string $endpoint, array $params): array
    {
        if (getenv('RAWG_DISABLED') === '1') {
           throw new \RuntimeException('RAWG temporarily disabled');
        }
        
        $key = getenv('RAWG_API_KEY');
        if (!$key) {
            throw new \RuntimeException('Missing RAWG_API_KEY');
        }
        // genre_slug is our internal field, not a RAWG API argument.
        unset($params['genre_slug']);
        $params['key'] = $key;
        $url = 'https://api.rawg.io/api/' . ltrim($endpoint, '/') . '?' . http_build_query($params);
        $ch = curl_init($url);
        if ($ch === false) {
            throw new \RuntimeException('Unable to initialize RAWG request.');
        }
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 7,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($body === false || $status === 0 || $status >= 500 || in_array($status, [401, 403, 429], true)) {
            throw new \RuntimeException('RAWG HTTP ' . $status . ' ' . $error);
        }
        if ($status === 404) {
            return [];
        }
        if ($status >= 400) {
            throw new \RuntimeException('RAWG HTTP ' . $status);
        }
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('RAWG invalid JSON');
        }
        return $decoded;
    }
}
