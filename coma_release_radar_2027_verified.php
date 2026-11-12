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
        }
        $next = $result['nextPageToken'] ?? null;
    } while ($next !== null && $next !== '');
    return $found;
}
function formatGame(string $id, array $releases): array {
    $first = $releases[0];
    $game = is_array($first['game'] ?? null) ? $first['game'] : [];
    $title = trim((string)($game['name'] ?? 'Untitled'));
    $dates = []; $platforms = [];
    foreach ($releases as $r) {
        $human = (string)($r['human'] ?? '');
        $month = (int)($r['m'] ?? 0);
        $date = isset($r['date']) ? gmdate('Y-m-d', (int)$r['date']) : '';
        $platformName = is_array($r['platform'] ?? null)
            ? ($r['platform']['name'] ?? 'Unknown') : 'Unknown';
        $platforms[] = $platformName;
        $dates[] = ($date ?: ($human ?: sprintf('%04d-%02d', YEAR, $month)))
            . ' — ' . $platformName;
    }
    $companies = [];
    foreach (($game['involved_companies'] ?? []) as $involved) {
        if (!is_array($involved)) continue;
        $name = is_array($involved['company'] ?? null)
            ? ($involved['company']['name'] ?? '') : '';
        if ($name !== '') $companies[] = $name . ' (' .
            (!empty($involved['developer']) ? 'developer' : (!empty($involved['publisher']) ? 'publisher' : 'company')) . ')';
    }
    $months = array_values(array_unique(array_filter(array_map(
        static fn($r) => (int)($r['m'] ?? 0), $releases),
        static fn($m) => $m >= 1 && $m <= 12)));
    sort($months);
    $description = "IGDB Game ID: {$id}\n"
        . "Release dates and platforms:\n" . implode("\n", array_unique($dates))
        . "\nStudios/publishers: " . (implode(', ', array_unique($companies)) ?: 'Not listed')
        . "\nIGDB: https://www.igdb.com/games/ (search title: {$title})"
        . "\nOfficial game website: " . ($game['url'] ?? 'Not listed')
        . "\nPress contact: Requires manual verification from official studio/publisher sources."
        . "\nEditorial follow-up: Manage status/assignee in Jira.";
    return [
        'summary' => '[2027 Release] ' . mb_substr($title, 0, 190),
        'description' => jiraDoc($description),
        'labels' => array_merge(['coma-radar-2027','igdb-game-' . $id],
            array_map(static fn($m) => sprintf('release-2027-%02d', $m), $months))
    ];
}
/**
 * ADF tables in Jira descriptions, automatically split into safe-sized parts.
 * No game-by-game issue creation.
 */
function cell(string $value, bool $header = false): array {
    $text = trim($value);
    return [
        'type' => $header ? 'tableHeader' : 'tableCell',
        'content' => [['type'=>'paragraph','content'=>$text === '' ? [] :
            [['type'=>'text','text'=>mb_substr($text, 0, 1200)]]]]
    ];
}
function monthlyTable(int $month, array $games): array {
    $header = ['Spel','Datum','Plattform','Studio / utgivare','IGDB-ID','Webbplats'];
    $rows = [['type'=>'tableRow','content'=>array_map(
        static fn($v) => cell($v, true), $header)]];
    foreach ($games as $id => $releases) {
        $game = is_array($releases[0]['game'] ?? null) ? $releases[0]['game'] : [];
        $dates = []; $platforms = []; $studios = [];
        foreach ($releases as $release) {
            $dates[] = isset($release['date'])
                ? gmdate('Y-m-d', (int)$release['date'])
                : (string)($release['human'] ?? sprintf('%04d-%02d', YEAR, $month));
            $platforms[] = is_array($release['platform'] ?? null)
                ? (string)($release['platform']['name'] ?? 'Okänd') : 'Okänd';
        }
        foreach (($game['involved_companies'] ?? []) as $c) {
            if (!is_array($c)) continue;
            $name = is_array($c['company'] ?? null) ? (string)($c['company']['name'] ?? '') : '';
            if ($name !== '') $studios[] = $name;
        }
        $values = [
            (string)($game['name'] ?? 'Utan namn'),
            implode(', ', array_unique($dates)),
            implode(', ', array_unique($platforms)),
            implode(', ', array_unique($studios)),
            (string)$id,
            (string)($game['url'] ?? '')
        ];
        $rows[] = ['type'=>'tableRow','content'=>array_map(
            static fn($v) => cell($v), $values)];
    }
    return ['type'=>'doc','version'=>1,'content'=>[
        ['type'=>'paragraph','content'=>[['type'=>'text',
            'text'=>sprintf('Spel som har utgivningsdatum i %04d-%02d enligt IGDB. Automatisk uppdatering; uppgifterna kan ändras.', YEAR, $month)]]],
        ['type'=>'table','attrs'=>['isNumberColumnEnabled'=>false,'layout'=>'default'],'content'=>$rows]
    ]];
}
// Conservative cap: even the JSON-encoded ADF document stays below 32,767 bytes.
// This avoids Jira's description size limit without dropping any games.
const MAX_DESCRIPTION_BYTES = 30000;

function monthlyTableParts(int $month, array $games): array {
    $parts = [];
    $current = [];
    foreach ($games as $gameId => $releases) {
        $candidate = $current;
        $candidate[$gameId] = $releases;
        $doc = monthlyTable($month, $candidate);
        $bytes = strlen(json_encode($doc, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
        if ($bytes > MAX_DESCRIPTION_BYTES) {
            if ($current === []) {
                throw new RuntimeException("Game {$gameId} in {$month} exceeds the Jira description limit by itself.");
            }
            $parts[] = $current;
            $current = [$gameId => $releases];
            $singleDoc = monthlyTable($month, $current);
            if (strlen(json_encode($singleDoc, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)) > MAX_DESCRIPTION_BYTES) {
                throw new RuntimeException("Game {$gameId} in {$month} exceeds the Jira description limit by itself.");
            }
        } else {
            $current = $candidate;
        }
    }
    $parts[] = $current; // Keep one empty table for months without games.
    return $parts;
}

function existingMonthlyTables(): array {
    $found = []; $next = null;
    do {
        $payload = ['jql'=>'project = KAN AND labels = "coma-radar-table-2027"',
            'maxResults'=>100,'fields'=>['labels','summary']];
        if ($next !== null) $payload['nextPageToken'] = $next;
        $response = jiraRequest('POST', '/rest/api/3/search/jql', $payload);
        foreach (($response['issues'] ?? []) as $issue) {
            $labels = $issue['fields']['labels'] ?? [];
            $month = null;
            $part = null;
            foreach ($labels as $label) {
                if (preg_match('/^release-2027-(0[1-9]|1[0-2])$/', $label, $m)) {
                    $month = (int)$m[1];
                }
                if (preg_match('/^release-2027-(0[1-9]|1[0-2])-part-([0-9]+)$/', $label, $m)) {
                    if ($part !== null) {
                        throw new RuntimeException("More than one part label on Jira issue {$issue['key']}.");
                    }
                    $part = (int)$m[2];
                    if ($part < 1) throw new RuntimeException("Invalid part label on {$issue['key']}.");
                    if ($month !== null && $month !== (int)$m[1]) {
                        throw new RuntimeException("Conflicting month labels on {$issue['key']}.");
                    }
                    $month = (int)$m[1];
                }
            }
            if ($month === null) continue;
            $part ??= 1; // Reuse an existing unsplit monthly issue as part 1.
            if (isset($found[$month][$part])) {
                throw new RuntimeException("Duplicate monthly table for {$month}, part {$part}; refusing update.");
            }
            $found[$month][$part] = $issue['key'];
        }
        $next = $response['nextPageToken'] ?? null;
    } while ($next !== null && $next !== '');
    return $found;
}

/** Confirm the actual Jira issue after each write; no false green success. */
function verifyMonthlyIssue(string $key, int $month, int $part, int $expectedGames): void {
    $issue = jiraRequest('GET', '/rest/api/3/issue/' . rawurlencode($key)
        . '?fields=summary,labels,description');
    $fields = $issue['fields'] ?? [];
    $monthLabel = sprintf('release-2027-%02d', $month);
    $partLabel = sprintf('release-2027-%02d-part-%02d', $month, $part);
    $labels = $fields['labels'] ?? [];
    if (!in_array('coma-radar-table-2027', $labels, true)
        || !in_array($monthLabel, $labels, true)
        || !in_array($partLabel, $labels, true)) {
        throw new RuntimeException("Jira verification failed for {$key}: missing month/part labels");
    }
    $doc = $fields['description'] ?? null;
    $table = null;
    foreach (($doc['content'] ?? []) as $node) {
        if (($node['type'] ?? '') === 'table') { $table = $node; break; }
    }
    if ($table === null) {
        throw new RuntimeException("Jira verification failed for {$key}: no game table");
    }
    $actualGames = max(0, count($table['content'] ?? []) - 1);
    if ($actualGames !== $expectedGames) {
        throw new RuntimeException("Jira verification failed for {$key}: expected {$expectedGames} games, found {$actualGames}");
    }
}

function main(): void {
    [$id, $token] = authenticateIgdb();
    $releases = fetchReleases($id, $token);
    $byMonth = array_fill_keys(range(1, 12), []);
    foreach ($releases as $gameId => $entries) {
        foreach ($entries as $entry) {
            $month = (int)($entry['m'] ?? 0);
            if ($month >= 1 && $month <= 12) {
                $byMonth[$month][(string)$gameId][] = $entry;
            }
        }
    }
    $dryRun = getenv('DRY_RUN') === '1';
    $existing = $dryRun ? [] : existingMonthlyTables();
    $verified = [];
    foreach ($byMonth as $month => $games) {
        ksort($games, SORT_NUMERIC);
        $parts = monthlyTableParts($month, $games);
        $totalParts = count($parts);
        foreach ($parts as $index => $partGames) {
            $part = $index + 1;
            $fields = [
                'summary'=>sprintf('[2027 spelöversikt] %04d-%02d – del %d/%d (%d spel)',
                    YEAR, $month, $part, $totalParts, count($partGames)),
                'description'=>monthlyTable($month, $partGames),
                'labels'=>['coma-radar-table-2027', sprintf('release-2027-%02d', $month),
                    sprintf('release-2027-%02d-part-%02d', $month, $part)]
            ];
            if (!$dryRun) {
                if (isset($existing[$month][$part])) {
                    $issueKey = $existing[$month][$part];
                    jiraRequest('PUT', '/rest/api/3/issue/' . rawurlencode($issueKey), ['fields'=>$fields]);
                    $operation = 'uppdaterat';
                } else {
                    $fields['project'] = ['key'=>'KAN'];
                    $fields['issuetype'] = ['name'=>'Task'];
                    $created = jiraRequest('POST', '/rest/api/3/issue', ['fields'=>$fields]);
                    $issueKey = (string)($created['key'] ?? '');
                    if ($issueKey === '') {
                        throw new RuntimeException("Jira returned no issue key for {$month} part {$part}");
                    }
                    $operation = 'skapat';
                }
                verifyMonthlyIssue($issueKey, $month, $part, count($partGames));
                $verified[$month][$part] = $issueKey;
                echo sprintf("BEKRÄFTAT %04d-%02d del %d/%d: %s (%d spel, %s)\n",
                    YEAR, $month, $part, $totalParts, $issueKey, count($partGames), $operation);
            }
        }
        // Previously created extra parts must not show stale games after a later sync.
        foreach (($existing[$month] ?? []) as $oldPart => $issueKey) {
            if ($oldPart <= $totalParts || $dryRun) continue;
            $fields = [
                'summary'=>sprintf('[2027 spelöversikt] %04d-%02d – del %d (inaktuell)', YEAR, $month, $oldPart),
                'description'=>jiraDoc('Denna del används inte längre. Alla aktuella spel finns i de andra delarna för månaden.'),
                'labels'=>['coma-radar-table-2027', sprintf('release-2027-%02d', $month),
                    sprintf('release-2027-%02d-part-%02d', $month, $oldPart)]
            ];
            jiraRequest('PUT', '/rest/api/3/issue/' . rawurlencode($issueKey), ['fields'=>$fields]);
        }
        echo sprintf("%04d-%02d: %d spel i %d del(ar); %s\n", YEAR, $month, count($games),
            $totalParts, $dryRun ? 'testkörning utan Jira-ändringar' : 'Jira-tabeller uppdaterade');
    }
    if (!$dryRun) {
        foreach (range(1, 12) as $month) {
            if (empty($verified[$month])) {
                throw new RuntimeException(sprintf('MONTH MISSING: %04d-%02d was not verified in Jira', YEAR, $month));
            }
        }
        echo "KLART: samtliga 12 månader är bekräftade i Jira.\n";
        echo "OBS: Om bara januari och februari syns trots detta, kontrollera Jira-filter/projektvy och ovanstående ärendenummer.\n";
    } else {
        echo "DRY_RUN: inga Jira-ärenden har skapats eller verifierats.\n";
    }
    echo "Inga individuella spelärenden skapas. Befintliga spelärenden raderas inte automatiskt.\n";
}

try { main(); } catch (Throwable $error) {
    fwrite(STDERR, $error->getMessage() . PHP_EOL);
    exit(1);
}