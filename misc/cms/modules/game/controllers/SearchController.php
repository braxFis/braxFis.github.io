<?php
namespace modules\game\controllers;

use modules\game\models\Search;

class SearchController
{
    public function index()
    {
        $results = [];
        $query = $_GET['q'] ?? null;

        if ($query) {
            $search = new Search();
            $games = $search->findGames($query);

            foreach ($games as $game) {
                $id = $game['id'];
                $description = $search->getDescription($id);
                $trailers = $search->getTrailers($id);

                $results[] = [
                    'name' => $game['name'],
                    'released' => $game['released'] ?? '',
                    'image' => $game['background_image'] ?? '',
                    'rating' => $game['rating'] ?? 'N/A',
                    'metacritic' => $game['metacritic'] ?? 'N/A',
                    'description' => $description,
                    'platforms' => $game['platforms'] ?? [],
                    'genres' => $game['genres'] ?? [],
                    'esrb' => $game['esrb_rating']['name'] ?? 'Not Rated',
                    'screenshots' => $game['short_screenshots'] ?? [],
                    'trailers' => array_filter($trailers)
                ];
            }
        }

        require __DIR__ . '/../views/search.php';
    }
}
