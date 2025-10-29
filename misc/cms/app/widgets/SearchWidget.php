<?php

namespace app\widgets;

use modules\game\models\Search;

class SearchWidget {
  public static function renderSearch(){
    $q = $_GET['q'];
    return '<form method="get" action="/search">
                <input type="text" name="q" id="search-query" placeholder="Search for a game..." value="{$q}">
                <button type="submit">Search</button>
            </form>';
  }
}
