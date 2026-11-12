
    
  
<?php
declare(strict_types=1);

/**
 * COMA Release Radar 2027: real IGDB release dates -> Jira Cloud issues.
 * PHP 8.2+ with curl. No credentials stored in this repository.
 */
const YEAR = 2027;
function envRequired(string $name): string {
    $value = trim((string) getenv($name));
    if ($value === '') { throw new RuntimeException("Missing environment variable: {$name}"); }
    return $value;
}
function request(string $method, string $url, array $headers = [], ?string $body = null): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 45,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_USERAGENT => 'COMA-Release-Radar/1.0'
    ]);
    if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    if ($response === false) throw new RuntimeException('HTTP request failed: ' . curl_error($ch));
    curl_close($ch);
    if ($status < 200 || $status >= 300) {
        throw new RuntimeException("HTTP {$status} from {$url}: " . substr((string)$response, 0, 1000));
    }
    if ($response === '' || $status === 204) return [];
    $parsed = json_decode((string)$response, true, 512, JSON_THROW_ON_ERROR);
    return is_array($parsed) ? $parsed : [];
}
function jiraDoc(string $text): array {
    return ['type'=>'doc','version'=>1,'content'=>[
        ['type'=>'paragraph','content'=>[['type'=>'text','text'=>$text]]]
    ]];
}
function jiraRequest(string $method, string $path, ?array $data = null): array {
    $base = rtrim(envRequired('JIRA_BASE_URL'), '/');
    $email = envRequired('JIRA_EMAIL');
    $token = envRequired('JIRA_API_TOKEN');
    $headers = ['Accept: application/json','Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($email . ':' . $token)];
    return request($method, $base . $path, $headers,
        $data === null ? null : json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
}
function igdb(string $endpoint, string $query, string $clientId, string $accessToken): array {
    return request('POST', 'https://api.igdb.com/v4/' . $endpoint,
        ['Client-ID: ' . $clientId,'Authorization: Bearer ' . $accessToken,
         'Content-Type: text/plain'], $query);
}
function authenticateIgdb(): array {
    $id = envRequired('IGDB_CLIENT_ID');
    $secret = envRequired('IGDB_CLIENT_SECRET');
    $url = 'https://id.twitch.tv/oauth2/token?' . http_build_query([
        'client_id'=>$id,'client_secret'=>$secret,'grant_type'=>'client_credentials']);
    $token = request('POST', $url);
    if (empty($token['access_token'])) throw new RuntimeException('IGDB access token missing');
    return [$id,(string)$token['access_token']];
}
function fetchReleases(string $id, string $token): array {
    $all = [];
    for ($offset = 0; $offset < 10000; $offset += 500) {
        $query = 'fields id, date, human, y, m, game.id, game.name, game.url, '
            . 'game.involved_companies.company.name, game.involved_companies.developer, '
            . 'game.involved_companies.publisher, platform.name; '
            . 'where y = ' . YEAR . ' & game != null; sort id asc; limit 500; offset ' . $offset . ';';
        $page = igdb('release_dates', $query, $id, $token);
        foreach ($page as $release) {
            $game = $release['game'] ?? null;
            $gameId = is_array($game) ? ($game['id'] ?? null) : $game;
            if (!$gameId) continue;
            $all[(string)$gameId][] = $release;
        }
        if (count($page) < 500) return $all;
        usleep(300000); // conservative request pacing
    }
    throw new RuntimeException('IGDB pagination cap reached; refusing partial sync.');
}
function existingRadarIssues(): array {
    $found = [];
    $next = null;
    do {
        $payload = ['jql'=>'project = KAN AND labels = "coma-radar-2027" ORDER BY key ASC',
            'maxResults'=>100,'fields'=>['labels','summary']];
        if ($next !== null) $payload['nextPageToken'] = $next;
        $result = jiraRequest('POST', '/rest/api/3/search/jql', $payload);
        foreach ($result['issues'] ?? [] as $issue) {
            foreach (($issue['fields']['labels'] ?? []) as $label) {
                if (preg_match('/^igdb-game-(\d+)$/', $label, $match)) {
                    $found[$match[1]] = $issue['key'];
                }
            }

