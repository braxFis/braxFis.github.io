
<style>
  /* =========================================================
     SEARCH - MAIN CONTENT
     ========================================================= */

  #search-section {
    width: 100%;
  }


  /* ---------------------------------------------------------
     HEADER
     --------------------------------------------------------- */

  .search-main-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;

    margin-bottom: 28px;
    padding-bottom: 15px;

    border-bottom: 2px solid #111;
  }

  .search-main-eyebrow {
    display: block;

    margin-bottom: 7px;

    color: #888;

    font-size: 9px;
    font-weight: 800;
    letter-spacing: 3px;
  }

  .search-main-title {
    margin: 0;

    color: #111;

    font-size: 36px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -1.5px;
  }

  .search-query-label {
    margin: 10px 0 0;
    color: #777;
    font-size: 13px;
    line-height: 1.4;
    overflow-wrap: anywhere;
  }

  .search-main-count {
    color: #888;

    font-size: 9px;
    font-weight: 800;
    letter-spacing: 2px;
  }


  /* ---------------------------------------------------------
     SECTION
     --------------------------------------------------------- */

  .search-result-section {
    margin-bottom: 45px;
  }

  .search-result-section-title {
    margin: 0 0 22px;

    color: #111;

    font-size: 13px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
  }


  /* ---------------------------------------------------------
     RESULT LIST
     --------------------------------------------------------- */

  .search-results {
    display: flex;
    flex-direction: column;
  }


  /* ---------------------------------------------------------
     GAME / MOVIE / MUSIC RESULT
     --------------------------------------------------------- */

  .search-game,
  .search-movie,
  .search-album {
    padding: 0 0 28px;
    margin-bottom: 28px;

    border-bottom: 1px solid #ddd;
  }


  .search-game {
    display: grid;

    grid-template-columns: 210px minmax(0, 1fr);

    gap: 22px;
  }


  .search-movie {
    display: grid;

    grid-template-columns: 140px minmax(0, 1fr);

    gap: 22px;
  }


  .search-album {
    display: grid;

    grid-template-columns: 180px minmax(0, 1fr);

    gap: 22px;
  }


  /* ---------------------------------------------------------
     GAME IMAGE
     --------------------------------------------------------- */

  .search-game-image {
    display: block;

    width: 100%;

    aspect-ratio: 16 / 10;

    overflow: hidden;

    background: #111;
  }

  .search-game-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;

    transition:
      transform 0.6s ease,
      filter 0.4s ease;
  }

  .search-game:hover .search-game-image img {
    transform: scale(1.05);
    filter: brightness(0.82);
  }


  /* ---------------------------------------------------------
     MOVIE IMAGE
     --------------------------------------------------------- */

  .search-movie-image {
    display: block;

    width: 100%;

    aspect-ratio: 2 / 3;

    overflow: hidden;

    background: #111;
  }

  .search-movie-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;

    transition:
      transform 0.6s ease,
      filter 0.4s ease;
  }

  .search-movie:hover .search-movie-image img {
    transform: scale(1.05);
    filter: brightness(0.82);
  }


  /* ---------------------------------------------------------
     ALBUM IMAGE
     --------------------------------------------------------- */

  .search-album-image {
    display: block;

    width: 100%;

    aspect-ratio: 1 / 1;

    overflow: hidden;

    background: #111;
  }

  .search-album-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;

    transition:
      transform 0.6s ease,
      filter 0.4s ease;
  }

  .search-album:hover .search-album-image img {
    transform: scale(1.05);
    filter: brightness(0.82);
  }


  /* ---------------------------------------------------------
     CONTENT
     --------------------------------------------------------- */

  .search-game-content,
  .search-movie-content,
  .search-album-content {
    min-width: 0;
  }


  /* ---------------------------------------------------------
     TOP
     --------------------------------------------------------- */

  .search-game-top,
  .search-movie-top,
  .search-album-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 12px;
  }

  .search-game-label,
  .search-movie-label,
  .search-album-label {
    display: block;

    margin-bottom: 5px;

    color: #888;

    font-size: 8px;
    font-weight: 800;
    letter-spacing: 2px;
  }

  .search-game-title,
  .search-movie-title,
  .search-album-title {
    margin: 0;

    color: #111;

    font-size: 24px;
    line-height: 1.05;
    font-weight: 800;

    letter-spacing: -0.8px;
  }

  .search-game-title a,
  .search-movie-title a,
  .search-album-title a {
    color: inherit;
    text-decoration: none;
  }

  .search-game-title a:hover,
  .search-movie-title a:hover,
  .search-album-title a:hover {
    color: #777;
  }


  /* ---------------------------------------------------------
     META
     --------------------------------------------------------- */

  .search-game-meta,
  .search-movie-meta,
  .search-album-meta {
    display: flex;

    flex-wrap: wrap;

    gap: 20px;

    margin-top: 18px;
    padding-top: 13px;

    border-top: 1px solid #eee;
  }

  .search-game-meta-item,
  .search-movie-meta-item,
  .search-album-meta-item {
    display: flex;

    flex-direction: column;

    gap: 4px;
  }

  .search-game-meta-item span,
  .search-movie-meta-item span,
  .search-album-meta-item span {
    color: #999;

    font-size: 7px;
    font-weight: 800;

    letter-spacing: 1.5px;
  }

  .search-game-meta-item strong,
  .search-movie-meta-item strong,
  .search-album-meta-item strong {
    color: #222;

    font-size: 12px;
    font-weight: 700;
  }


  /* ---------------------------------------------------------
     NO RESULTS
     --------------------------------------------------------- */

  .search-no-results {
    color: #777;

    font-size: 13px;
    line-height: 1.55;
  }


  /* ---------------------------------------------------------
     MOBILE
     --------------------------------------------------------- */

  @media (max-width: 700px) {

    .search-main-header {
      margin-bottom: 22px;
    }

    .search-main-title {
      font-size: 30px;
    }

    .search-game,
    .search-movie,
    .search-album {
      grid-template-columns: 1fr;

      gap: 15px;

      padding-bottom: 25px;
      margin-bottom: 25px;
    }

    .search-game-image {
      aspect-ratio: 16 / 9;
    }

    .search-movie-image {
      width: 180px;

      aspect-ratio: 2 / 3;
    }

    .search-album-image {
      width: 180px;
    }

    .search-game-title,
    .search-movie-title,
    .search-album-title {
      font-size: 22px;
    }

    .search-person {
      grid-template-columns: 1fr;

      gap: 15px;

      padding-bottom: 25px;
      margin-bottom: 25px;
    }

    .search-person-image {
      width: 180px;
    }

    .search-person-title {
      font-size: 22px;
    }
  }

  /* ---------------------------------------------------------
   PEOPLE RESULT
   --------------------------------------------------------- */

  .search-person {
    display: grid;

    grid-template-columns: 140px minmax(0, 1fr);

    gap: 22px;

    padding: 0 0 28px;
    margin-bottom: 28px;

    border-bottom: 1px solid #ddd;
  }


  .search-person-image {
    display: block;

    width: 100%;

    aspect-ratio: 2 / 3;

    overflow: hidden;

    background: #111;
  }


  .search-person-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;

    transition:
      transform 0.6s ease,
      filter 0.4s ease;
  }


  .search-person:hover .search-person-image img {
    transform: scale(1.05);
    filter: brightness(0.82);
  }


  .search-person-content {
    min-width: 0;
  }


  .search-person-label {
    display: block;

    margin-bottom: 5px;

    color: #888;

    font-size: 8px;
    font-weight: 800;
    letter-spacing: 2px;
  }


  .search-person-title {
    margin: 0;

    color: #111;

    font-size: 24px;
    line-height: 1.05;
    font-weight: 800;

    letter-spacing: -0.8px;
  }


  .search-person-meta {
    display: flex;

    flex-wrap: wrap;

    gap: 20px;

    margin-top: 18px;
    padding-top: 13px;

    border-top: 1px solid #eee;
  }


  .search-person-meta-item {
    display: flex;

    flex-direction: column;

    gap: 4px;
  }


  .search-person-meta-item span {
    color: #999;

    font-size: 7px;
    font-weight: 800;

    letter-spacing: 1.5px;
  }


  .search-person-meta-item strong {
    color: #222;

    font-size: 12px;
    font-weight: 700;
  }
</style>



<section id="search-section">

  <?php
  $results = is_array($results ?? null) ? $results : [];
  $movies = is_array($movies ?? null) ? $movies : [];
  $albums = is_array($albums ?? null) ? $albums : [];
  $people = is_array($people ?? null) ? $people : [];
  $year = $year ?? null;
  $query = $query ?? ($_GET['q'] ?? '');

  $totalResults = count($results)
    + count($movies)
    + count($albums)
    + count($people);
  ?>


  <?php if ($totalResults > 0): ?>

    <header class="search-main-header">

      <div>

        <span class="search-main-eyebrow">
          SEARCH
        </span>

        <h1 class="search-main-title">
          Search Results
        </h1>

        <?php if (trim((string) $query) !== ''): ?>
          <p class="search-query-label">
            Results for &ldquo;<?= htmlspecialchars((string) $query, ENT_QUOTES, 'UTF-8') ?>&rdquo;
          </p>
        <?php elseif (!empty($year)): ?>
          <p class="search-query-label">
            Albums from <?= (int) $year ?>
          </p>
        <?php endif; ?>

      </div>

      <span class="search-main-count">
        <?= $totalResults ?> RESULTS
      </span>

    </header>


    <!-- =====================================================
    GAMES
    ====================================================== -->

    <?php if (!empty($results)): ?>

      <section class="search-result-section">

        <h2 class="search-result-section-title">
          Games
        </h2>

        <div class="search-results">

          <?php foreach ($results as $item): ?>
            <?php
              $gameId = rawurlencode((string)($item['id'] ?? ''));
              $gameName = (string) ($item['name'] ?? '');
              // GameDataService must resolve the ID from the provider used.
              $gameLink = '/games/' . $gameId;

              // Use the normalized image first, then common RAWG/IGDB fields.
              $gameImage = !empty($item['image'])
                ? $item['image']
                : ($item['background_image'] ?? '');
              if (!$gameImage && !empty($item['cover']['url'])) {
                $gameImage = $item['cover']['url'];
              }
              if (!$gameImage && !empty($item['cover']['image_id'])) {
                $gameImage = 'https://images.igdb.com/igdb/image/upload/t_cover_big/'
                  . $item['cover']['image_id'] . '.jpg';
              }
              if (is_string($gameImage) && substr($gameImage, 0, 2) === '//') {
                $gameImage = 'https:' . $gameImage;
              }
              $gameImage = is_string($gameImage) ? $gameImage : '';

              $releaseDate = $item['released'] ?? $item['first_release_date'] ?? null;
              if (is_numeric($releaseDate) && (int) $releaseDate > 100000000) {
                $releaseDate = gmdate('Y-m-d', (int) $releaseDate);
              }

              $rating = $item['rating'] ?? null;
              if ($rating === null && isset($item['total_rating'])) {
                // IGDB total_rating is on a 0-100 scale; display as 0-5.
                $rating = round((float) $item['total_rating'] / 20, 2);
              }
              $metacritic = $item['metacritic'] ?? null;

              $genreNames = [];
              foreach (($item['genres'] ?? []) as $genre) {
                $name = is_array($genre) ? ($genre['name'] ?? '') : (is_string($genre) ? $genre : '');
                if ($name !== '') $genreNames[] = $name;
              }

              $platformNames = [];
              foreach (($item['platforms'] ?? []) as $platform) {
                if (is_array($platform)) {
                  $name = $platform['platform']['name'] ?? $platform['name'] ?? '';
                } else {
                  $name = is_string($platform) ? $platform : '';
                }
                if ($name !== '') $platformNames[] = $name;
              }
            ?>

            <article class="search-game">
              <?php if ($gameImage !== ''): ?>
                <a class="search-game-image" href="<?= htmlspecialchars($gameLink, ENT_QUOTES, 'UTF-8') ?>">
                  <img src="<?= htmlspecialchars($gameImage, ENT_QUOTES, 'UTF-8') ?>"
                       alt="<?= htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                </a>
              <?php endif; ?>

              <div class="search-game-content">
                <div class="search-game-top">
                  <div>
                    <span class="search-game-label">GAME</span>
                    <h2 class="search-game-title">
                      <a href="<?= htmlspecialchars($gameLink, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8') ?>
                      </a>
                    </h2>
                  </div>
                  <?php if (!empty($releaseDate)): ?>
                    <span><?= htmlspecialchars((string) $releaseDate, ENT_QUOTES, 'UTF-8') ?></span>
                  <?php endif; ?>
                </div>

                <?php if ($rating !== null || $metacritic !== null || $genreNames || $platformNames): ?>
                  <div class="search-game-meta">
                    <?php if ($rating !== null): ?>
                      <div class="search-game-meta-item">
                        <span>RATING</span>
                        <strong><?= htmlspecialchars((string) $rating, ENT_QUOTES, 'UTF-8') ?></strong>
                      </div>
                    <?php endif; ?>
                    <?php if ($metacritic !== null): ?>
                      <div class="search-game-meta-item">
                        <span>METACRITIC</span>
                        <strong><?= htmlspecialchars((string) $metacritic, ENT_QUOTES, 'UTF-8') ?></strong>
                      </div>
                    <?php endif; ?>
                    <?php if ($genreNames): ?>
                      <div class="search-game-meta-item">
                        <span>GENRE</span>
                        <strong><?= htmlspecialchars(implode(', ', $genreNames), ENT_QUOTES, 'UTF-8') ?></strong>
                      </div>
                    <?php endif; ?>
                    <?php if ($platformNames): ?>
                      <div class="search-game-meta-item">
                        <span>PLATFORMS</span>
                        <strong><?= htmlspecialchars(implode(', ', $platformNames), ENT_QUOTES, 'UTF-8') ?></strong>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>

        </div>

      </section>

    <?php endif; ?>


    <!-- =====================================================
    MOVIES
    ====================================================== -->

    <?php if (!empty($movies)): ?>

      <section class="search-result-section">

        <h2 class="search-result-section-title">
          Movies
        </h2>

        <div class="search-results">

          <?php foreach ($movies as $movie): ?>

            <?php
            $poster = !empty($movie['poster_path'])
              ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
              : '';
            ?>

            <article class="search-movie">

              <?php if ($poster): ?>

                <a
                  class="search-movie-image"
                  href="/movies/<?= (int)$movie['id'] ?>"
                >
                  <img
                    src="<?= htmlspecialchars($poster) ?>"
                    alt="<?= htmlspecialchars($movie['title'] ?? '') ?>"
                  >
                </a>

              <?php endif; ?>


              <div class="search-movie-content">

                <div class="search-movie-top">

                  <div>

                    <span class="search-movie-label">
                      MOVIE
                    </span>

                    <h2 class="search-movie-title">

                      <a href="/movies/<?= (int)$movie['id'] ?>">
                        <?= htmlspecialchars($movie['title'] ?? '') ?>
                      </a>

                    </h2>

                  </div>


                  <?php if (!empty($movie['release_date'])): ?>

                    <span>
                      <?= htmlspecialchars($movie['release_date']) ?>
                    </span>

                  <?php endif; ?>

                </div>


                <div class="search-movie-meta">

                  <?php if (isset($movie['vote_average'])): ?>

                    <div class="search-movie-meta-item">

                      <span>RATING</span>

                      <strong>
                        <?= number_format(
                          (float)$movie['vote_average'],
                          1
                        ) ?>
                      </strong>

                    </div>

                  <?php endif; ?>


                  <?php if (!empty($movie['original_title'])): ?>

                    <div class="search-movie-meta-item">

                      <span>ORIGINAL TITLE</span>

                      <strong>
                        <?= htmlspecialchars($movie['original_title']) ?>
                      </strong>

                    </div>

                  <?php endif; ?>

                </div>

              </div>

            </article>

          <?php endforeach; ?>

        </div>

      </section>

    <?php endif; ?>


    <!-- =====================================================
    MUSIC
    ====================================================== -->
    <form method="get" action="/search" class="search-filter">

      <?php if (!empty($query)): ?>
        <input
          type="hidden"
          name="q"
          value="<?= htmlspecialchars($query) ?>"
        >
      <?php endif; ?>

      <label for="year">
        Album release year
      </label>

      <select name="year" id="year">

        <option value="">All years</option>

        <?php for ($yearOption = date('Y'); $yearOption >= 2000; $yearOption--): ?>

          <option
            value="<?= $yearOption ?>"
            <?= $year == $yearOption ? 'selected' : '' ?>
          >
            <?= $yearOption ?>
          </option>

        <?php endfor; ?>

      </select>

      <button type="submit">
        Filter
      </button>

    </form>
    <?php if (!empty($albums)): ?>

      <section class="search-result-section">

        <h2 class="search-result-section-title">
          Music
        </h2>

        <div class="search-results">

          <?php foreach ($albums as $album): ?>

            <?php
            $albumId = $album['id'] ?? '';
            $albumName = $album['name'] ?? '';
            $albumImage = $album['images'][0]['url'] ?? '';

            $artists = [];

            if (!empty($album['artists'])) {
              foreach ($album['artists'] as $artist) {
                if (!empty($artist['name'])) {
                  $artists[] = $artist['name'];
                }
              }
            }

            $artistNames = implode(', ', $artists);
            ?>

            <article class="search-album">

              <?php if ($albumImage && $albumId): ?>

                <a
                  class="search-album-image"
                  href="/music/album/<?= htmlspecialchars($albumId) ?>"
                >
                  <img
                    src="<?= htmlspecialchars($albumImage) ?>"
                    alt="<?= htmlspecialchars($albumName) ?>"
                  >
                </a>

              <?php endif; ?>


              <div class="search-album-content">

                <div class="search-album-top">

                  <div>

                    <span class="search-album-label">
                      ALBUM
                    </span>

                    <h2 class="search-album-title">

                      <?php if ($albumId): ?>

                        <a href="/music/album/<?= htmlspecialchars($albumId) ?>">
                          <?= htmlspecialchars($albumName) ?>
                        </a>

                      <?php else: ?>

                        <?= htmlspecialchars($albumName) ?>

                      <?php endif; ?>

                    </h2>

                  </div>

                </div>


                <div class="search-album-meta">

                  <?php if ($artistNames): ?>

                    <div class="search-album-meta-item">

                      <span>ARTIST</span>

                      <strong>
                        <?= htmlspecialchars($artistNames) ?>
                      </strong>

                    </div>

                  <?php endif; ?>


                  <?php if (!empty($album['album_type'])): ?>

                    <div class="search-album-meta-item">

                      <span>TYPE</span>

                      <strong>
                        <?= htmlspecialchars(
                          ucfirst($album['album_type'])
                        ) ?>
                      </strong>

                    </div>

                  <?php endif; ?>


                  <?php if (!empty($album['release_date'])): ?>

                    <div class="search-album-meta-item">

                      <span>RELEASED</span>

                      <strong>
                        <?= htmlspecialchars($album['release_date']) ?>
                      </strong>

                    </div>

                  <?php endif; ?>

                </div>

              </div>

            </article>

          <?php endforeach; ?>

        </div>

      </section>

    <?php endif; ?>


    <!-- =====================================================
    PEOPLE
    ====================================================== -->

<?php if (!empty($people)): ?>

  <section class="search-result-section">

    <h2 class="search-result-section-title">
      People
    </h2>

    <div class="search-results">

      <?php foreach ($people as $person): ?>

        <?php
        $personName = $person['name'] ?? '';
        $personImage = !empty($person['profile_path'])
          ? 'https://image.tmdb.org/t/p/w500' . $person['profile_path']
          : '';

        $knownFor = [];

        if (!empty($person['known_for'])) {
          foreach ($person['known_for'] as $known) {

            $title =
              $known['title']
              ?? $known['name']
              ?? '';

            if ($title) {
              $knownFor[] = $title;
            }
          }
        }
        ?>

        <article class="search-person">

          <?php if ($personImage): ?>

            <div class="search-person-image">

              <img
                src="<?= htmlspecialchars($personImage) ?>"
                alt="<?= htmlspecialchars($personName) ?>"
              >

            </div>

          <?php endif; ?>


          <div class="search-person-content">

            <span class="search-person-label">
              PERSON
            </span>

            <a
              href="/people/<?= (int)$person['id'] ?>"
              class="search-person-title"
            >
              <?= htmlspecialchars($personName) ?>
            </a>

            <?php if (!empty($knownFor)): ?>

              <div class="search-person-meta">

                <div class="search-person-meta-item">

                  <span>KNOWN FOR</span>

                  <strong>
                    <?= htmlspecialchars(
                      implode(', ', array_slice($knownFor, 0, 4))
                    ) ?>
                  </strong>

                </div>

              </div>

            <?php endif; ?>

          </div>

        </article>

      <?php endforeach; ?>

    </div>

  </section>

<?php endif; ?>


  <?php elseif (trim((string) $query) !== '' || !empty($year)): ?>

    <header class="search-main-header">

      <div>

        <span class="search-main-eyebrow">
          SEARCH
        </span>

        <h1 class="search-main-title">
          Search Results
        </h1>

        <?php if (trim((string) $query) !== ''): ?>
          <p class="search-query-label">
            Results for &ldquo;<?= htmlspecialchars((string) $query, ENT_QUOTES, 'UTF-8') ?>&rdquo;
          </p>
        <?php elseif (!empty($year)): ?>
          <p class="search-query-label">
            Albums from <?= (int) $year ?>
          </p>
        <?php endif; ?>

      </div>

      <span class="search-main-count">
        0 RESULTS
      </span>

    </header>

    <p class="search-no-results">
      Inga resultat hittades för
      "<?= htmlspecialchars((string) $query, ENT_QUOTES, 'UTF-8') ?>"
    </p>

  <?php endif; ?>

</section>

