<?php
namespace app\services;

/** Turns provider-specific data into the array shape consumed by current PHP views. */
final class GameDataAdapter
{
    public function fromRawg(array $game): array
    {
        $game['rating_source'] = 'RAWG';
        return $game;
    }

    public function fromIgdb(array $game): array
    {
        $image = $game['cover']['url'] ?? null;
        if (is_string($image) && $image !== '') {
            if (str_starts_with($image, '//')) $image = 'https:' . $image;
            $image = str_replace('t_thumb', 't_cover_big', $image);
        } else {
            $image = null;
        }
        $named = static function ($items): array {
            $result = [];
            foreach (is_array($items) ? $items : [] as $item) {
                if (is_array($item) && isset($item['name'])) {
                    $result[] = ['name' => (string)$item['name'], 'slug' => $item['slug'] ?? ''];
                }
            }
            return $result;
        };
        $companies = $game['involved_companies'] ?? [];
        $byRole = static function (string $role) use ($companies): array {
            $out = [];
            foreach ($companies as $entry) {
                if (!empty($entry[$role]) && !empty($entry['company']['name'])) {
                    $out[] = ['name' => $entry['company']['name'], 'slug' => ''];
                }
            }
            return $out;
        };
        return [
            'id' => 'igdb-' . (int)($game['id'] ?? 0),
            'name' => $game['name'] ?? 'Unknown game',
            'slug' => $game['slug'] ?? '',
            'background_image' => $image,
            'released' => isset($game['first_release_date']) ? gmdate('Y-m-d', (int)$game['first_release_date']) : null,
            'rating' => isset($game['rating']) ? round((float)$game['rating']/20, 1) : null,
            'rating_source' => 'IGDB',
            'metacritic' => null, // IGDB critic rating is NOT Metacritic.
            'esrb_rating' => null, // Needs a verified ESRB category mapping.
            'description' => $game['summary'] ?? '',
            'description_raw' => $game['summary'] ?? '',
            'genres' => $named($game['genres'] ?? []),
            'platforms' => array_map(static fn($x) => ['platform'=> ['name'=>$x['name'], 'slug'=>$x['slug'] ?? '']], $named($game['platforms'] ?? [])),
            'developers' => $byRole('developer'),
            'publishers' => $byRole('publisher'),
            'stores' => [],
            'short_screenshots' => [],
            'trailers' => [],
        ];
    }
}
