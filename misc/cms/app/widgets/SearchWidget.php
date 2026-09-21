<?php

namespace app\widgets;

class SearchWidget
{
  public static function renderSearch()
  {
    $q = htmlspecialchars(
      $_GET['q'] ?? '',
      ENT_QUOTES,
      'UTF-8'
    );

    return '
            <form method="get" action="/search">
                <input
                    type="text"
                    name="q"
                    id="search-query"
                    placeholder="Search for a game, movie or song..."
                    value="' . $q . '"
                >
                <!--<button type="submit">Search</button>-->
            </form>
        ';
  }
}
