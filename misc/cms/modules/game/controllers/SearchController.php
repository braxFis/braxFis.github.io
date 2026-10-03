<?php

namespace modules\game\controllers;

use modules\game\models\Search;

class SearchController
{
  public function index()
  {
    $results = [];
    $query = trim($_GET['q'] ?? '');
    $year = (int)($_GET['year'] ?? 0);

    $movies = [];
    $albums = [];
    $people = [];

    if ($query !== '') {

      $search = new Search();

      // =====================================================
      // GAMES
      // =====================================================

      $games = $search->findGames($query);

      foreach ($games as $game) {

        $results[] = [
          'id'          => $game['id'] ?? 0,
          'name'        => $game['name'] ?? '',
          'released'    => $game['released'] ?? '',
          'image'       => $game['background_image'] ?? '',
          'rating'      => $game['rating'] ?? 'N/A',
          'metacritic'  => $game['metacritic'] ?? 'N/A',
          'platforms'   => $game['platforms'] ?? [],
          'genres'      => $game['genres'] ?? [],
          'esrb'        => $game['esrb_rating']['name'] ?? 'Not Rated',
          'screenshots' => $game['short_screenshots'] ?? []
        ];
      }


      // =====================================================
      // MOVIES
      // =====================================================

      $movies = $search->findMovies($query);


      // =====================================================
      // ALBUMS
      // =====================================================

      $albums = $search->findAlbums($query);


      // =====================================================
      // ALBUM DATE FILTER
      // =====================================================

      if ($year >= 1900) {
        $albums = $search->findAlbumsByYear($year);
      }


      // =====================================================
      // PEOPLE
      // =====================================================

      $people = $search->findPeople($query);
    }


    // =====================================================
    // VIEW
    // =====================================================

    ob_start();
    require __DIR__ . '/../views/search.php';
    $content = ob_get_clean();

    require __DIR__ . '/../../../app/views/layout.php';
  }
}
