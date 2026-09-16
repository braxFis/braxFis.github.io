<style>
  /* =========================================================
   COMA NEWS - GAME DETAIL
   ========================================================= */

  .game-page {
    width: min(1100px, 92%);
    margin: 60px auto 100px;
  }


  /* ---------------------------------------------------------
     HERO
     --------------------------------------------------------- */

  .game-hero {
    margin-bottom: 55px;
  }

  .game-hero-top {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;

    gap: 30px;

    margin-bottom: 22px;
  }

  .game-page-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .game-page-title {
    margin: 0;

    color: #111;

    font-size: clamp(40px, 6vw, 72px);

    line-height: .95;

    font-weight: 800;

    letter-spacing: -3px;
  }

  .game-page-rating {
    display: flex;
    flex-direction: column;

    align-items: flex-end;

    flex-shrink: 0;
  }

  .game-page-rating span {
    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .game-page-rating strong {
    color: #111;

    font-size: 26px;
  }


  /* ---------------------------------------------------------
     HERO IMAGE
     --------------------------------------------------------- */

  .game-hero-image {
    position: relative;

    width: 100%;

    aspect-ratio: 21 / 9;

    overflow: hidden;

    background: #111;
  }

  .game-hero-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;
  }

  .game-hero-image-overlay {
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

  .game-hero-meta {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    border-bottom: 1px solid #ddd;
  }

  .game-hero-meta > div {
    padding: 18px 20px;

    border-right: 1px solid #ddd;
  }

  .game-hero-meta > div:first-child {
    padding-left: 0;
  }

  .game-hero-meta > div:last-child {
    border-right: 0;
  }

  .game-hero-meta span {
    display: block;

    margin-bottom: 5px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .game-hero-meta strong {
    color: #111;

    font-size: 14px;
  }


  /* ---------------------------------------------------------
     INFORMATION
     --------------------------------------------------------- */

  .game-information {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr) 260px;

    gap: 60px;

    margin-bottom: 60px;
  }

  .game-section-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .game-description-column h2 {
    margin: 0 0 20px;

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -1px;
  }

  .game-description-full {
    color: #444;

    font-size: 15px;
    line-height: 1.75;
  }

  .game-description-full p {
    margin-top: 0;
  }


  /* ---------------------------------------------------------
     FACTS
     --------------------------------------------------------- */

  .game-facts {
    border-top: 2px solid #111;
  }

  .game-fact {
    padding: 16px 0;

    border-bottom: 1px solid #ddd;
  }

  .game-fact > span {
    display: block;

    margin-bottom: 7px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .game-fact p {
    margin: 0;

    color: #333;

    font-size: 12px;
    line-height: 1.5;
  }

  .game-store-list {
    display: flex;

    flex-wrap: wrap;

    gap: 6px;
  }

  .game-store-list a {
    color: #333;

    font-size: 11px;
    font-weight: 700;

    text-decoration: none;
  }

  .game-store-list a:hover {
    text-decoration: underline;
  }


  /* ---------------------------------------------------------
     MOBILE
     --------------------------------------------------------- */

  @media (max-width: 700px) {

    .game-page {
      margin-top: 35px;
    }

    .game-hero-top {
      align-items: flex-start;
    }

    .game-page-title {
      font-size: 42px;
      letter-spacing: -2px;
    }

    .game-page-rating {
      display: none;
    }

    .game-hero-image {
      aspect-ratio: 16 / 9;
    }

    .game-hero-meta {
      grid-template-columns: 1fr;
    }

    .game-hero-meta > div,
    .game-hero-meta > div:first-child {
      padding: 14px 0;

      border-right: 0;
      border-bottom: 1px solid #ddd;
    }

    .game-information {
      grid-template-columns: 1fr;

      gap: 35px;
    }

  }
</style>
<?php

use app\widgets\PictureWidget;
use app\widgets\TrailerWidget;

$description = $game['description_raw']
  ?? strip_tags($game['description'] ?? '');

$platforms = array_map(
  fn($p) => $p['platform']['name'],
  $game['platforms'] ?? []
);

$genres = array_map(
  fn($g) => $g['name'],
  $game['genres'] ?? []
);

$stores = $game['stores'] ?? [];

?>

<div class="game-page">

  <!-- =====================================================
       HERO
       ===================================================== -->

  <section class="game-hero">

    <div class="game-hero-top">

      <div>

                <span class="game-page-eyebrow">
                    GAME
                </span>

        <h1 class="game-page-title">
          <?= htmlspecialchars($game['name']) ?>
        </h1>

      </div>


      <div class="game-page-rating">

                <span>
                    RATING
                </span>

        <strong>
          <?= htmlspecialchars(
            $game['rating'] ?? 'N/A'
          ) ?>
        </strong>

      </div>

    </div>


    <?php if (!empty($game['background_image'])): ?>

      <div class="game-hero-image">

        <img
          src="<?= htmlspecialchars($game['background_image']) ?>"
          alt="<?= htmlspecialchars($game['name']) ?>"
        >

        <div class="game-hero-image-overlay"></div>

      </div>

    <?php endif; ?>


    <div class="game-hero-meta">

      <div>

        <span>RELEASE</span>

        <strong>
          <?= htmlspecialchars(
            $game['released'] ?? 'TBA'
          ) ?>
        </strong>

      </div>


      <div>

        <span>METACRITIC</span>

        <strong>
          <?= htmlspecialchars(
            $game['metacritic'] ?? 'N/A'
          ) ?>
        </strong>

      </div>


      <div>

        <span>ESRB</span>

        <strong>
          <?= htmlspecialchars(
            $game['esrb_rating']['name']
            ?? 'Not Rated'
          ) ?>
        </strong>

      </div>

    </div>

  </section>


  <!-- =====================================================
       INFORMATION
       ===================================================== -->

  <section class="game-information">

    <div class="game-description-column">

            <span class="game-section-eyebrow">
                ABOUT THE GAME
            </span>

      <h2>
        <?= htmlspecialchars($game['name']) ?>
      </h2>

      <div class="game-description-full">

        <?= $description ?>

      </div>

    </div>


    <aside class="game-facts">

      <?php if (!empty($platforms)): ?>

        <div class="game-fact">

          <span>PLATFORMS</span>

          <p>
            <?= htmlspecialchars(
              implode(', ', $platforms)
            ) ?>
          </p>

        </div>

      <?php endif; ?>


      <?php if (!empty($genres)): ?>

        <div class="game-fact">

          <span>GENRES</span>

          <p>
            <?= htmlspecialchars(
              implode(', ', $genres)
            ) ?>
          </p>

        </div>

      <?php endif; ?>


      <?php if (!empty($stores)): ?>

        <div class="game-fact">

          <span>AVAILABLE AT</span>

          <div class="game-store-list">

            <?php foreach ($stores as $store): ?>

              <a
                href="https://<?= htmlspecialchars(
                  $store['store']['domain']
                ) ?>"
                target="_blank"
                rel="noopener noreferrer"
              >
                <?= htmlspecialchars(
                  $store['store']['name']
                ) ?>
              </a>

            <?php endforeach; ?>

          </div>

        </div>

      <?php endif; ?>

    </aside>

  </section>


  <!-- =====================================================
       SCREENSHOTS
       ===================================================== -->

  <?= PictureWidget::renderImageSideBar(
    $game['id']
  ) ?>


  <!-- =====================================================
       TRAILERS
       ===================================================== -->

  <?= TrailerWidget::renderTrailerSideBar(
    $game['id']
  ) ?>

</div>
