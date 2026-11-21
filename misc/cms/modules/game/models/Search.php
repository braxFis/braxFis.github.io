<?php

namespace modules\game\models;

use app\services\GameDataService;
use app\models\TMDB_API;
use modules\music\models\Music;

class Search
{
    private GameDataService $gameService;

    public function __construct()
    {
        $this->gameService = new GameDataService();
    }

public function findGames(
    string $query,
    int $pageSize = 10
): array {
    $query = trim($query);

    if ($query === '') {
        return [];
    }

    $data = $this->gameService->fetch('games', [
        'search' => $query,
        'page_size' => max(1, $pageSize)
    ]);

    if (!is_array($data)) {
        return [];
    }

    if (isset($data['results']) && is_array($data['results'])) {
        return $data['results'];
    }

    if (array_is_list($data)) {
        return $data;
    }

    return [];
    }
    
    public function findMovies(string $query): array
    {
        $tmdb = new TMDB_API();

        return $tmdb->searchMovies($query);
    }

    public function findAlbums(string $query): array
    {
        $music = new Music();

        $data = $music->search($query, 'album');

        return $data['albums']['items'] ?? [];
    }

    public function findAlbumsByYear(int $year): array
    {
        $music = new Music();

        $albums = [];

        for ($page = 0; $page < 10; $page++) {

            $offset = $page * 10;

            $data = $music->search(
                'year:' . $year,
                'album',
                $offset
            );

            $items = $data['albums']['items'] ?? [];

            if (empty($items)) {
                break;
            }

            $albums = array_merge($albums, $items);

            if (count($items) < 10) {
                break;
            }
        }

        usort($albums, function ($a, $b) {
            return strcmp(
                $b['release_date'] ?? '',
                $a['release_date'] ?? ''
            );
        });

        return $albums;
    }

    public function findPeople(string $query): array
    {
        $tmdb = new TMDB_API();

        return $tmdb->searchPeople($query);
    }
}
