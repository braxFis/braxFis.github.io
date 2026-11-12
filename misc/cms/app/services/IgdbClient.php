<?php

declare(strict_types=1);

namespace app\services;

/** Server-side IGDB client; requires IGDB_CLIENT_ID and IGDB_CLIENT_SECRET. */
class IgdbClient
{
    private static ?string $cachedToken = null;
    private static int $tokenExpiresAt = 0;
    private static array $entityIds = [];

    private function token(): string
    {
        if (self::$cachedToken !== null && time() < self::$tokenExpiresAt) {
            return self::$cachedToken;
        }
        $id = getenv('IGDB_CLIENT_ID');
        $secret = getenv('IGDB_CLIENT_SECRET');
        if (!is_string($id) || $id === '' || !is_string($secret) || $secret === '') {
            throw new \RuntimeException('IGDB credentials missing. Check config/.env and bootstrap.php.');
        }
        $ch = curl_init('https://id.twitch.tv/oauth2/token');
        if ($ch === false) {
            throw new \RuntimeException('Unable to initialize Twitch token request.');
        }
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'client_id' => $id,
                'client_secret' => $secret,
                'grant_type' => 'client_credentials',
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        //curl_close($ch);
        $data = is_string($body) ? json_decode($body, true) : null;
        if ($body === false || $status !== 200 || !is_array($data) || empty($data['access_token'])) {
            throw new \RuntimeException('Twitch token request failed (HTTP ' . $status . '). ' . $error);
        }
        self::$cachedToken = (string) $data['access_token'];
        self::$tokenExpiresAt = time() + max(1, (int) ($data['expires_in'] ?? 3600) - 60);
        return self::$cachedToken;
    }

    private function request(string $query, string $endpoint = 'games'): array
    {
        if (!in_array($endpoint, ['games', 'game_videos', 'screenshots', 'genres', 'themes', 'game_modes'], true)) {
            throw new \InvalidArgumentException('Unsupported IGDB endpoint.');
        }
        $id = getenv('IGDB_CLIENT_ID');
        if (!is_string($id) || $id === '') {
            throw new \RuntimeException('IGDB_CLIENT_ID missing.');
        }
        $token = $this->token();
        $ch = curl_init('https://api.igdb.com/v4/' . $endpoint);
        if ($ch === false) {
            throw new \RuntimeException('Unable to initialize IGDB request.');
        }
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 9,
            CURLOPT_HTTPHEADER => [
                'Client-ID: ' . $id,
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
                'Content-Type: text/plain',
            ],
        ]);
        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        //curl_close($ch);
        if ($body === false || $status !== 200) {
            throw new \RuntimeException('IGDB ' . $endpoint . ' request failed (HTTP ' . $status . '). ' . $error);
        }
        $data = json_decode($body, true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('IGDB returned invalid JSON for ' . $endpoint . '.');
        }
        return $data;
    }

    /** Resolve a genre/theme/mode by its IGDB name, not a RAWG numeric ID. */
    private function resolveEntityId(string $type, string $name): ?int
    {
        if (!in_array($type, ['genres', 'themes', 'game_modes'], true)) {
            throw new \InvalidArgumentException('Unsupported filter category.');
        }
        $cacheKey = $type . ':' . $name;
        if (array_key_exists($cacheKey, self::$entityIds)) {
            return self::$entityIds[$cacheKey];
        }
        // Fetch the relatively small vocabulary once and match exact names in PHP.
        // Avoid guessing numeric IDs or interpolating external names into Apicalypse.
        $items = $this->request('fields id,name; limit 500;', $type);
        foreach ($items as $item) {
            if (isset($item['id'], $item['name']) && strcasecmp((string)$item['name'], $name) === 0) {
                return self::$entityIds[$cacheKey] = (int) $item['id'];
            }
        }
        self::$entityIds[$cacheKey] = null;
        error_log('IGDB entity not found: ' . $cacheKey);
        return null;
    }

    /** RAWG-style parameters translated to IGDB Apicalypse. */
    public function games(array $params): array
    {
        $page = max(1, (int) ($params['page'] ?? 1));
        $limit = min(50, max(1, (int) ($params['page_size'] ?? 20)));
        $offset = ($page - 1) * $limit;
        $search = trim((string) ($params['search'] ?? ''));
        $conditions = [];

        if (isset($params['igdb_filter'])) {
            $filter = $params['igdb_filter'];
            if (!is_array($filter) || !isset($filter['type'], $filter['name'])) {
                throw new \InvalidArgumentException('Invalid IGDB filter.');
            }
            $type = (string) $filter['type'];
            $name = (string) $filter['name'];
            $id = $this->resolveEntityId($type, $name);
            if ($id === null) {
                return ['results' => [], 'notice' => 'Ingen motsvarande IGDB-kategori hittades.'];
            }
            $conditions[] = $type . ' = (' . $id . ')';
        } elseif (isset($params['genres'])) {
            // Never accidentally interpret an unconverted RAWG genre ID as an IGDB genre ID.
            return ['results' => [], 'notice' => 'RAWG genre-ID saknar IGDB-mappning.'];
        }

        $ordering = (string) ($params['ordering'] ?? '');
        $sort = 'sort first_release_date desc; ';
        $dates = (string) ($params['dates'] ?? '');
        if ($dates !== '') {
            $parts = explode(',', $dates, 2);
            if (count($parts) !== 2 || !$this->validDate($parts[0]) || !$this->validDate($parts[1])) {
                throw new \InvalidArgumentException('Invalid dates format; expected YYYY-MM-DD,YYYY-MM-DD.');
            }
            $from = strtotime($parts[0] . ' 00:00:00 UTC');
            $to = strtotime($parts[1] . ' 23:59:59 UTC');
            if ($from === false || $to === false || $to < $from) {
                throw new \InvalidArgumentException('Invalid date range.');
            }
            $conditions[] = 'first_release_date >= ' . $from;
            $conditions[] = 'first_release_date <= ' . $to;
            $sort = 'sort first_release_date asc; ';
        } elseif ($ordering === '-rating') {
            $conditions[] = 'rating != null';
            $conditions[] = 'rating_count >= 5';
            $sort = 'sort rating desc; ';
        } else {
            $conditions[] = 'first_release_date != null';
        }

        $fields = 'fields id,name,slug,summary,first_release_date,cover.url,rating,' .
            'genres.id,genres.name,genres.slug,platforms.id,platforms.name,platforms.slug,' .
            'game_modes.name,involved_companies.company.name,' .
            'involved_companies.developer,involved_companies.publisher; ';
        $query = '';
        if ($search !== '') {
            $escaped = str_replace(["\\", '"', "\r", "\n"], ["\\\\", '\\"', ' ', ' '], $search);
            $query .= 'search "' . $escaped . '"; ';
        }
        $query .= $fields;
        if ($conditions !== []) {
            $query .= 'where ' . implode(' & ', $conditions) . '; ';
        }
        // Search relevance is the default; otherwise apply the requested ordering.
        if ($search === '') {
            $query .= $sort;
        }
        $query .= 'limit ' . $limit . '; offset ' . $offset . ';';

        $games = $this->request($query);
        //error_log('IGDB first game: ' . json_encode($games[0] ?? []));
        $adapter = new GameDataAdapter();
        return ['results' => array_map([$adapter, 'fromIgdb'], $games)];
    }

    private function validDate(string $date): bool
    {
        $d = \DateTimeImmutable::createFromFormat('!Y-m-d', $date, new \DateTimeZone('UTC'));
        return $d !== false && $d->format('Y-m-d') === $date;
    }

    public function game(int $id): ?array
    {
        if ($id <= 0) return null;
        $query = 'fields id,name,slug,summary,first_release_date,cover.url,rating,' .
            'genres.id,genres.name,genres.slug,platforms.id,platforms.name,platforms.slug,' .
            'involved_companies.company.name,involved_companies.developer,' .
            'involved_companies.publisher; where id = ' . $id . '; limit 1;';
        $items = $this->request($query);
        return isset($items[0]) && is_array($items[0])
            ? (new GameDataAdapter())->fromIgdb($items[0]) : null;
    }

    public function trailers(int $id): array
    {
        if ($id <= 0) return [];
        return $this->request('fields name,video_id; where game = ' . $id . '; limit 50;', 'game_videos');
    }

    public function screenshots(int $id): array
    {
        if ($id <= 0) return [];
        return $this->request('fields image_id,width,height; where game = ' . $id . '; limit 50;', 'screenshots');
    }
}
