<style>
  /* =========================================================
     COMA NEWS - ALBUM DETAIL
     ========================================================= */

  .album-page {
    width: min(1100px, 92%);
    margin: 60px auto 100px;
  }


  /* ---------------------------------------------------------
     HERO
     --------------------------------------------------------- */

  .album-hero {
    margin-bottom: 55px;
  }

  .album-hero-top {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;

    gap: 30px;

    margin-bottom: 22px;
  }

  .album-page-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .album-page-title {
    margin: 0;

    color: #111;

    font-size: clamp(40px, 6vw, 72px);

    line-height: .95;

    font-weight: 800;

    letter-spacing: -3px;
  }


  /* ---------------------------------------------------------
     HERO IMAGE
     --------------------------------------------------------- */

  .album-hero-image {
    position: relative;

    width: 100%;

    aspect-ratio: 21 / 9;

    overflow: hidden;

    background: #111;
  }

  .album-hero-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;
  }

  .album-hero-image-overlay {
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

  .album-hero-meta {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    border-bottom: 1px solid #ddd;
  }

  .album-hero-meta > div {
    padding: 18px 20px;

    border-right: 1px solid #ddd;
  }

  .album-hero-meta > div:first-child {
    padding-left: 0;
  }

  .album-hero-meta > div:last-child {
    border-right: 0;
  }

  .album-hero-meta span {
    display: block;

    margin-bottom: 5px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .album-hero-meta strong {
    color: #111;

    font-size: 14px;
  }


  /* ---------------------------------------------------------
     INFORMATION
     --------------------------------------------------------- */

  .album-information {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr) 260px;

    gap: 60px;

    margin-bottom: 60px;
  }

  .album-section-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .album-description-column h2 {
    margin: 0 0 20px;

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -1px;
  }

  .album-description-full {
    color: #444;

    font-size: 15px;
    line-height: 1.75;
  }

  .album-description-full p {
    margin-top: 0;
  }


  /* ---------------------------------------------------------
     FACTS
     --------------------------------------------------------- */

  .album-facts {
    border-top: 2px solid #111;
  }

  .album-fact {
    padding: 16px 0;

    border-bottom: 1px solid #ddd;
  }

  .album-fact > span {
    display: block;

    margin-bottom: 7px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .album-fact p {
    margin: 0;

    color: #333;

    font-size: 12px;
    line-height: 1.5;
  }

  .album-list {
    display: flex;

    flex-wrap: wrap;

    gap: 6px;
  }

  .album-list a {
    color: #333;

    font-size: 11px;
    font-weight: 700;

    text-decoration: none;
  }

  .album-list a:hover {
    text-decoration: underline;
  }


  /* ---------------------------------------------------------
     TRACKS
     --------------------------------------------------------- */

  .album-tracks {
    margin-bottom: 60px;
  }

  .album-tracks-header {
    margin-bottom: 20px;
  }

  .album-tracks-header h2 {
    margin: 0;

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -1px;
  }

  .album-track {
    display: grid;

    grid-template-columns: 45px minmax(0, 1fr) 80px;

    align-items: center;

    gap: 15px;

    padding: 14px 0;

    border-top: 1px solid #ddd;
  }

  .album-track:last-child {
    border-bottom: 1px solid #ddd;
  }

  .album-track-number {
    color: #999;

    font-size: 10px;
    font-weight: 800;
  }

  .album-track-name {
    color: #111;

    font-size: 13px;
    font-weight: 700;
  }

  .album-track-duration {
    color: #999;

    font-size: 10px;
    text-align: right;
  }


  /* ---------------------------------------------------------
     MOBILE
     --------------------------------------------------------- */

  @media (max-width: 700px) {

    .album-page {
      margin-top: 35px;
    }

    .album-page-title {
      font-size: 42px;

      letter-spacing: -2px;
    }

    .album-hero-image {
      aspect-ratio: 1 / 1;
    }

    .album-hero-meta {
      grid-template-columns: 1fr;
    }

    .album-hero-meta > div,
    .album-hero-meta > div:first-child {
      padding: 14px 0;

      border-right: 0;
      border-bottom: 1px solid #ddd;
    }

    .album-information {
      grid-template-columns: 1fr;

      gap: 35px;
    }

    .album-track {
      grid-template-columns:
          35px minmax(0, 1fr) 65px;
    }

  }
</style>


<?php

/*
 * ---------------------------------------------------------
 * ALBUM DATA
 * ---------------------------------------------------------
 */

$title = $album['name']
  ?? 'Untitled';


$albumType = $album['album_type']
  ?? 'album';


$releaseDate = $album['release_date']
  ?? 'TBA';


$totalTracks = $album['total_tracks']
  ?? 0;


$label = $album['label']
  ?? '';


$genres = $album['genres']
  ?? [];


$artists = $album['artists']
  ?? [];


/*
 * ---------------------------------------------------------
 * SPOTIFY IMAGE
 * ---------------------------------------------------------
 */

$image = null;

if (!empty($album['images'])) {

  $image =
    $album['images'][0]['url']
    ?? null;

}


/*
 * ---------------------------------------------------------
 * ARTISTS
 * ---------------------------------------------------------
 */

$artistNames = [];

foreach ($artists as $artist) {

  if (!empty($artist['name'])) {

    $artistNames[] =
      $artist['name'];

  }

}


/*
 * ---------------------------------------------------------
 * TRACKS
 * ---------------------------------------------------------
 */

$tracks = $album['tracks']['items']
  ?? [];

?>


<div class="album-page">


  <!-- =====================================================
       HERO
       ===================================================== -->

  <section class="album-hero">

    <div class="album-hero-top">

      <div>

        <span class="album-page-eyebrow">
          <?= htmlspecialchars(
            strtoupper($albumType)
          ) ?>
        </span>

        <h1 class="album-page-title">
          <?= htmlspecialchars($title) ?>
        </h1>

      </div>

    </div>


    <?php if (!empty($image)): ?>

      <div class="album-hero-image">

        <img
          src="<?= htmlspecialchars($image) ?>"
          alt="<?= htmlspecialchars($title) ?>"
        >

        <div class="album-hero-image-overlay"></div>

      </div>

    <?php endif; ?>


    <div class="album-hero-meta">

      <div>

        <span>RELEASE</span>

        <strong>
          <?= htmlspecialchars($releaseDate) ?>
        </strong>

      </div>


      <div>

        <span>TRACKS</span>

        <strong>
          <?= htmlspecialchars(
            (string) $totalTracks
          ) ?>
        </strong>

      </div>


      <div>

        <span>ARTIST</span>

        <strong>
          <?= htmlspecialchars(
            implode(', ', $artistNames)
          ) ?>
        </strong>

      </div>

    </div>

  </section>


  <!-- =====================================================
       INFORMATION
       ===================================================== -->

  <section class="album-information">

    <div class="album-description-column">

      <span class="album-section-eyebrow">
        ABOUT THE ALBUM
      </span>

      <h2>
        <?= htmlspecialchars($title) ?>
      </h2>


      <?php if (!empty($artistNames)): ?>

        <div class="album-description-full">

          <p>

            <?= htmlspecialchars(
              implode(', ', $artistNames)
            ) ?>

          </p>

        </div>

      <?php endif; ?>

    </div>


    <aside class="album-facts">


      <!-- ARTISTS -->

      <?php if (!empty($artists)): ?>

        <div class="album-fact">

          <span>ARTISTS</span>

          <div class="album-list">

            <?php foreach ($artists as $artist): ?>

              <?php if (!empty($artist['id'])): ?>

                <a
                  href="/artist/<?= htmlspecialchars(
                    $artist['id']
                  ) ?>"
                >
                  <?= htmlspecialchars(
                    $artist['name']
                  ) ?>
                </a>

              <?php endif; ?>

            <?php endforeach; ?>

          </div>

        </div>

      <?php endif; ?>


      <!-- GENRES -->

      <?php if (!empty($genres)): ?>

        <div class="album-fact">

          <span>GENRES</span>

          <div class="album-list">

            <?php foreach ($genres as $genre): ?>

              <span>
                <?= htmlspecialchars($genre) ?>
              </span>

            <?php endforeach; ?>

          </div>

        </div>

      <?php endif; ?>


      <!-- LABEL -->

      <?php if (!empty($label)): ?>

        <div class="album-fact">

          <span>LABEL</span>

          <p>
            <?= htmlspecialchars($label) ?>
          </p>

        </div>

      <?php endif; ?>


      <!-- TYPE -->

      <div class="album-fact">

        <span>TYPE</span>

        <p>
          <?= htmlspecialchars(
            ucfirst($albumType)
          ) ?>
        </p>

      </div>


    </aside>

  </section>


  <!-- =====================================================
       TRACKS
       ===================================================== -->

  <section class="album-tracks">

    <div class="album-tracks-header">

      <span class="album-section-eyebrow">
        TRACKLIST
      </span>

      <h2>
        Tracks
      </h2>

    </div>


    <?php foreach ($tracks as $index => $track): ?>

      <?php

      $trackName =
        $track['name']
        ?? 'Untitled';

      $duration =
        $track['duration_ms']
        ?? 0;

      $minutes =
        floor($duration / 60000);

      $seconds =
        floor(
          ($duration % 60000) / 1000
        );

      $durationText =
        $minutes
        . ':'
        . str_pad(
          $seconds,
          2,
          '0',
          STR_PAD_LEFT
        );

      ?>

      <div class="album-track">

        <span class="album-track-number">

          <?= str_pad(
            $index + 1,
            2,
            '0',
            STR_PAD_LEFT
          ) ?>

        </span>


        <span class="album-track-name">

          <?= htmlspecialchars(
            $trackName
          ) ?>

        </span>


        <span class="album-track-duration">

          <?= htmlspecialchars(
            $durationText
          ) ?>

        </span>

      </div>

    <?php endforeach; ?>

  </section>

</div>
