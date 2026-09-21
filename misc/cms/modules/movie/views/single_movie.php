<style>
  /* =========================================================
     COMA NEWS - MOVIE DETAIL
     ========================================================= */

  .movie-page {
    width: min(1100px, 92%);
    margin: 60px auto 100px;
  }


  /* ---------------------------------------------------------
     HERO
     --------------------------------------------------------- */

  .movie-hero {
    margin-bottom: 55px;
  }

  .movie-hero-top {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;

    gap: 30px;

    margin-bottom: 22px;
  }

  .movie-page-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .movie-page-title {
    margin: 0;

    color: #111;

    font-size: clamp(40px, 6vw, 72px);

    line-height: .95;

    font-weight: 800;

    letter-spacing: -3px;
  }

  .movie-page-rating {
    display: flex;
    flex-direction: column;

    align-items: flex-end;

    flex-shrink: 0;
  }

  .movie-page-rating span {
    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .movie-page-rating strong {
    color: #111;

    font-size: 26px;
  }


  /* ---------------------------------------------------------
     HERO IMAGE
     --------------------------------------------------------- */

  .movie-hero-image {
    position: relative;

    width: 100%;

    aspect-ratio: 21 / 9;

    overflow: hidden;

    background: #111;
  }

  .movie-hero-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;
  }

  .movie-hero-image-overlay {
    position: absolute;

    inset: 0;

    background:
      linear-gradient(
        to bottom,
        transparent 45%,
        rgba(0,0,0,.35)
      );
  }


  /* ---------------------------------------------------------
     HERO META
     --------------------------------------------------------- */

  .movie-hero-meta {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    border-bottom: 1px solid #ddd;
  }

  .movie-hero-meta > div {
    padding: 18px 20px;

    border-right: 1px solid #ddd;
  }

  .movie-hero-meta > div:first-child {
    padding-left: 0;
  }

  .movie-hero-meta > div:last-child {
    border-right: 0;
  }

  .movie-hero-meta span {
    display: block;

    margin-bottom: 5px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .movie-hero-meta strong {
    color: #111;

    font-size: 14px;
  }


  /* ---------------------------------------------------------
     INFORMATION
     --------------------------------------------------------- */

  .movie-information {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr) 260px;

    gap: 60px;

    margin-bottom: 60px;
  }

  .movie-section-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .movie-description-column h2 {
    margin: 0 0 20px;

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -1px;
  }

  .movie-description-full {
    color: #444;

    font-size: 15px;
    line-height: 1.75;
  }

  .movie-description-full p {
    margin-top: 0;
  }


  /* ---------------------------------------------------------
     FACTS
     --------------------------------------------------------- */

  .movie-facts {
    border-top: 2px solid #111;
  }

  .movie-fact {
    padding: 16px 0;

    border-bottom: 1px solid #ddd;
  }

  .movie-fact > span {
    display: block;

    margin-bottom: 7px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .movie-fact p {
    margin: 0;

    color: #333;

    font-size: 12px;
    line-height: 1.5;
  }

  .movie-list {
    display: flex;

    flex-wrap: wrap;

    gap: 6px;
  }

  .movie-list a {
    color: #333;

    font-size: 11px;
    font-weight: 700;

    text-decoration: none;
  }

  .movie-list a:hover {
    text-decoration: underline;
  }


  /* ---------------------------------------------------------
     MOBILE
     --------------------------------------------------------- */

  @media (max-width: 700px) {

    .movie-page {
      margin-top: 35px;
    }

    .movie-hero-top {
      align-items: flex-start;
    }

    .movie-page-title {
      font-size: 42px;
      letter-spacing: -2px;
    }

    .movie-page-rating {
      display: none;
    }

    .movie-hero-image {
      aspect-ratio: 16 / 9;
    }

    .movie-hero-meta {
      grid-template-columns: 1fr;
    }

    .movie-hero-meta > div,
    .movie-hero-meta > div:first-child {
      padding: 14px 0;

      border-right: 0;
      border-bottom: 1px solid #ddd;
    }

    .movie-information {
      grid-template-columns: 1fr;

      gap: 35px;
    }

  }

  .coma-trailer-video {
    width: 100%;
    aspect-ratio: 16 / 9;
    position: relative;
  }

  .coma-trailer-video iframe {
    width: 100%;
    height: 100%;
    display: block;
    border: 0;
  }
</style>

<?php

use app\widgets\PictureWidget;
use app\widgets\TrailerWidget;


/*
 * ---------------------------------------------------------
 * MOVIE DATA
 * ---------------------------------------------------------
 */

$title = $movie['title']
  ?? $movie['original_title']
  ?? 'Untitled';


$description = $movie['overview']
  ?? '';


$releaseDate = $movie['release_date']
  ?? 'TBA';


$rating = isset($movie['vote_average'])
  ? number_format(
    (float) $movie['vote_average'],
    1
  )
  : 'N/A';


$genres = $movie['genres']
  ?? [];


$productionCompanies = $movie['production_companies']
  ?? [];


/*
 * ---------------------------------------------------------
 * TMDB IMAGES
 * ---------------------------------------------------------
 */

$backdrop = null;

if (!empty($movie['backdrop_path'])) {

  $backdrop =
    'https://image.tmdb.org/t/p/w1280'
    . $movie['backdrop_path'];

}


/*
 * ---------------------------------------------------------
 * RUNTIME
 * ---------------------------------------------------------
 */

$runtime = $movie['runtime']
  ?? null;

$runtimeText = 'N/A';

if (!empty($runtime)) {

  $hours = floor($runtime / 60);
  $minutes = $runtime % 60;

  if ($hours > 0) {

    $runtimeText =
      $hours . 'h '
      . $minutes . 'm';

  } else {

    $runtimeText =
      $minutes . ' min';

  }
}

?>

<div class="movie-page">

  <!-- =====================================================
       HERO
       ===================================================== -->

  <section class="movie-hero">

    <div class="movie-hero-top">

      <div>

        <span class="movie-page-eyebrow">
          MOVIE
        </span>

        <h1 class="movie-page-title">
          <?= htmlspecialchars($title) ?>
        </h1>

      </div>


      <div class="movie-page-rating">

        <span>
          RATING
        </span>

        <strong>
          <?= htmlspecialchars($rating) ?>
        </strong>

      </div>

    </div>


    <?php if (!empty($backdrop)): ?>

      <div class="movie-hero-image">

        <img
          src="<?= htmlspecialchars($backdrop) ?>"
          alt="<?= htmlspecialchars($title) ?>"
        >

        <div class="movie-hero-image-overlay"></div>

      </div>

    <?php endif; ?>


    <div class="movie-hero-meta">

      <div>

        <span>RELEASE</span>

        <strong>
          <?= htmlspecialchars($releaseDate) ?>
        </strong>

      </div>


      <div>

        <span>RUNTIME</span>

        <strong>
          <?= htmlspecialchars($runtimeText) ?>
        </strong>

      </div>


      <div>

        <span>POPULARITY</span>

        <strong>
          <?= htmlspecialchars($rating) ?>
        </strong>

      </div>

    </div>

  </section>


  <!-- =====================================================
       INFORMATION
       ===================================================== -->

  <section class="movie-information">

    <div class="movie-description-column">

      <span class="movie-section-eyebrow">
        ABOUT THE MOVIE
      </span>

      <h2>
        <?= htmlspecialchars($title) ?>
      </h2>

      <div class="movie-description-full">

        <?php if (!empty($description)): ?>

          <p>
            <?= nl2br(htmlspecialchars($description)) ?>
          </p>

        <?php else: ?>

          <p>
            No description available.
          </p>

        <?php endif; ?>

      </div>

    </div>


    <aside class="movie-facts">


      <!-- GENRES -->

      <?php if (!empty($genres)): ?>

        <div class="movie-fact">

          <span>GENRES</span>

          <div class="movie-list">

            <?php foreach ($genres as $genre): ?>

              <?php if (is_array($genre)): ?>

                <a
                  href="/genre/<?= htmlspecialchars(
                    $genre['id']
                  ) ?>"
                >
                  <?= htmlspecialchars(
                    $genre['name']
                  ) ?>
                </a>

              <?php endif; ?>

            <?php endforeach; ?>

          </div>

        </div>

      <?php endif; ?>


      <!-- PRODUCTION COMPANIES -->

      <?php if (!empty($productionCompanies)): ?>

        <div class="movie-fact">

          <span>PRODUCTION</span>

          <div class="movie-list">

            <?php foreach ($productionCompanies as $company): ?>

              <?php if (is_array($company)): ?>

                <span>
                  <?= htmlspecialchars(
                    $company['name']
                  ) ?>
                </span>

              <?php endif; ?>

            <?php endforeach; ?>

          </div>

        </div>

      <?php endif; ?>


      <!-- LANGUAGE -->

      <?php if (!empty($movie['original_language'])): ?>

        <div class="movie-fact">

          <span>LANGUAGE</span>

          <p>
            <?= htmlspecialchars(
              strtoupper(
                $movie['original_language']
              )
            ) ?>
          </p>

        </div>

      <?php endif; ?>


      <!-- STATUS -->

      <?php if (!empty($movie['status'])): ?>

        <div class="movie-fact">

          <span>STATUS</span>

          <p>
            <?= htmlspecialchars(
              $movie['status']
            ) ?>
          </p>

        </div>

      <?php endif; ?>


    </aside>

  </section>


  <!-- =====================================================
       TRAILERS
       ===================================================== -->

  <?= TrailerWidget::renderMovieTrailerSideBar($movie['id']) ?>

  <!-- =====================================================
       SCREENSHOTS
       =====================================================
  -->
  <?= PictureWidget::renderMovieImageSideBar($movie['id']);?>
</div>
