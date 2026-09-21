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
  GAME / MOVIE RESULT
  --------------------------------------------------------- */

  .search-game {
    display: grid;

    grid-template-columns: 210px minmax(0, 1fr);

    gap: 22px;

    padding: 0 0 28px;
    margin-bottom: 28px;

    border-bottom: 1px solid #ddd;
  }

  .search-movie {
    display: grid;

    grid-template-columns: 140px minmax(0, 1fr);

    gap: 22px;

    padding: 0 0 28px;
    margin-bottom: 28px;

    border-bottom: 1px solid #ddd;
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
  CONTENT
  --------------------------------------------------------- */

  .search-game-content,
  .search-movie-content {
    min-width: 0;
  }


  /* ---------------------------------------------------------
  TOP
  --------------------------------------------------------- */

  .search-game-top,
  .search-movie-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 12px;
  }

  .search-game-label,
  .search-movie-label {
    display: block;

    margin-bottom: 5px;

    color: #888;

    font-size: 8px;
    font-weight: 800;
    letter-spacing: 2px;
  }

  .search-game-title,
  .search-movie-title {
    margin: 0;

    color: #111;

    font-size: 24px;
    line-height: 1.05;
    font-weight: 800;

    letter-spacing: -0.8px;
  }

  .search-game-title a,
  .search-movie-title a {
    color: inherit;
    text-decoration: none;
  }

  .search-game-title a:hover,
  .search-movie-title a:hover {
    color: #777;
  }

  .search-game-release,
  .search-movie-release {
    flex-shrink: 0;

    color: #777;

    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1px;
  }


  /* ---------------------------------------------------------
  META
  --------------------------------------------------------- */

  .search-game-meta,
  .search-movie-meta {
    display: flex;

    flex-wrap: wrap;

    gap: 20px;

    margin-top: 18px;
    padding-top: 13px;

    border-top: 1px solid #eee;
  }

  .search-game-meta-item,
  .search-movie-meta-item {
    display: flex;

    flex-direction: column;

    gap: 4px;
  }

  .search-game-meta-item span,
  .search-movie-meta-item span {
    color: #999;

    font-size: 7px;
    font-weight: 800;

    letter-spacing: 1.5px;
  }

  .search-game-meta-item strong,
  .search-movie-meta-item strong {
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
    .search-movie {
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

    .search-game-title,
    .search-movie-title {
      font-size: 22px;
    }
  }
</style>


<section id="search-section">

  <?php
  $totalResults = count($results) + count($movies);
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

            <article class="search-game">

              <?php if (!empty($item['image'])): ?>

                <a
                  class="search-game-image"
                  href="/games/<?= (int)$item['id'] ?>"
                >
                  <img
                    src="<?= htmlspecialchars($item['image']) ?>"
                    alt="<?= htmlspecialchars($item['name']) ?>"
                  >
                </a>

              <?php endif; ?>


              <div class="search-game-content">

                <div class="search-game-top">

                  <div>

                    <span class="search-game-label">
                      GAME
                    </span>

                    <h2 class="search-game-title">

                      <a href="/games/<?= (int)$item['id'] ?>">
                        <?= htmlspecialchars($item['name']) ?>
                      </a>

                    </h2>

                  </div>


                  <?php if (!empty($item['released'])): ?>

                    <span class="search-game-release">
                      <?= htmlspecialchars($item['released']) ?>
                    </span>

                  <?php endif; ?>

                </div>


                <div class="search-game-meta">

                  <div class="search-game-meta-item">

                    <span>RATING</span>

                    <strong>
                      <?= htmlspecialchars($item['rating']) ?>
                    </strong>

                  </div>


                  <div class="search-game-meta-item">

                    <span>METACRITIC</span>

                    <strong>
                      <?= htmlspecialchars($item['metacritic']) ?>
                    </strong>

                  </div>


                  <?php if (!empty($item['genres'])): ?>

                    <div class="search-game-meta-item">

                      <span>GENRE</span>

                      <strong>
                        <?= htmlspecialchars(
                          implode(
                            ', ',
                            array_column(
                              $item['genres'],
                              'name'
                            )
                          )
                        ) ?>
                      </strong>

                    </div>

                  <?php endif; ?>


                  <?php if (!empty($item['platforms'])): ?>

                    <div class="search-game-meta-item">

                      <span>PLATFORMS</span>

                      <strong>
                        <?= htmlspecialchars(
                          implode(
                            ', ',
                            array_column(
                              array_column(
                                $item['platforms'],
                                'platform'
                              ),
                              'name'
                            )
                          )
                        ) ?>
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

                    <span class="search-movie-release">
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


  <?php elseif (!empty($_GET['q'])): ?>

    <header class="search-main-header">

      <div>

        <span class="search-main-eyebrow">
          SEARCH
        </span>

        <h1 class="search-main-title">
          Search Results
        </h1>

      </div>

      <span class="search-main-count">
        0 RESULTS
      </span>

    </header>

    <p class="search-no-results">
      Inga resultat hittades för
      "<?= htmlspecialchars($_GET['q']) ?>"
    </p>

  <?php endif; ?>

</section>
