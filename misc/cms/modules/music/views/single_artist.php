<style>
  /* =========================================================
     COMA NEWS - ARTIST DETAIL
     ========================================================= */

  .artist-page {
    width: min(1100px, 92%);
    margin: 60px auto 100px;
  }


  /* ---------------------------------------------------------
     HERO
     --------------------------------------------------------- */

  .artist-hero {
    margin-bottom: 55px;
  }

  .artist-page-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .artist-page-title {
    margin: 0 0 22px;

    color: #111;

    font-size: clamp(40px, 6vw, 72px);

    line-height: .95;

    font-weight: 800;

    letter-spacing: -3px;
  }


  /* ---------------------------------------------------------
     HERO IMAGE
     --------------------------------------------------------- */

  .artist-hero-image {
    position: relative;

    width: 100%;

    aspect-ratio: 21 / 9;

    overflow: hidden;

    background: #111;
  }

  .artist-hero-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;
  }

  .artist-hero-image-overlay {
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

  .artist-hero-meta {
    display: grid;

    grid-template-columns:
      repeat(3, 1fr);

    border-bottom: 1px solid #ddd;
  }

  .artist-hero-meta > div {
    padding: 18px 20px;

    border-right: 1px solid #ddd;
  }

  .artist-hero-meta > div:first-child {
    padding-left: 0;
  }

  .artist-hero-meta > div:last-child {
    border-right: 0;
  }

  .artist-hero-meta span {
    display: block;

    margin-bottom: 5px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .artist-hero-meta strong {
    color: #111;

    font-size: 14px;
  }


  /* ---------------------------------------------------------
     INFORMATION
     --------------------------------------------------------- */

  .artist-information {
    display: grid;

    grid-template-columns:
      minmax(0, 1fr) 260px;

    gap: 60px;

    margin-bottom: 60px;
  }

  .artist-section-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .artist-description-column h2 {
    margin: 0 0 20px;

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -1px;
  }

  .artist-description-full {
    color: #444;

    font-size: 15px;
    line-height: 1.75;
  }

  .artist-description-full p {
    margin-top: 0;
  }


  /* ---------------------------------------------------------
     FACTS
     --------------------------------------------------------- */

  .artist-facts {
    border-top: 2px solid #111;
  }

  .artist-fact {
    padding: 16px 0;

    border-bottom: 1px solid #ddd;
  }

  .artist-fact > span {
    display: block;

    margin-bottom: 7px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .artist-fact p {
    margin: 0;

    color: #333;

    font-size: 12px;
    line-height: 1.5;
  }

  .artist-list {
    display: flex;

    flex-wrap: wrap;

    gap: 6px;
  }

  .artist-list span {
    color: #333;

    font-size: 11px;
    font-weight: 700;
  }


  /* ---------------------------------------------------------
     ALBUMS
     --------------------------------------------------------- */

  .artist-albums {
    margin-bottom: 60px;
  }

  .artist-albums-header {
    margin-bottom: 20px;
  }

  .artist-albums-header h2 {
    margin: 0;

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -1px;
  }

  .artist-album {
    display: grid;

    grid-template-columns:
      80px minmax(0, 1fr) 100px;

    align-items: center;

    gap: 20px;

    padding: 14px 0;

    border-top: 1px solid #ddd;
  }

  .artist-album:last-child {
    border-bottom: 1px solid #ddd;
  }

  .artist-album-image {
    width: 80px;
    height: 80px;

    overflow: hidden;

    background: #111;
  }

  .artist-album-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;
  }

  .artist-album-name {
    color: #111;

    font-size: 13px;
    font-weight: 700;

    text-decoration: none;
  }

  .artist-album-name:hover {
    text-decoration: underline;
  }

  .artist-album-type {
    color: #999;

    font-size: 10px;
    font-weight: 800;

    text-align: right;

    letter-spacing: 1px;
  }


  /* ---------------------------------------------------------
     MOBILE
     --------------------------------------------------------- */

  @media (max-width: 700px) {

    .artist-page {
      margin-top: 35px;
    }

    .artist-page-title {
      font-size: 42px;

      letter-spacing: -2px;
    }

    .artist-hero-image {
      aspect-ratio: 1 / 1;
    }

    .artist-hero-meta {
      grid-template-columns: 1fr;
    }

    .artist-hero-meta > div,
    .artist-hero-meta > div:first-child {
      padding: 14px 0;

      border-right: 0;
      border-bottom: 1px solid #ddd;
    }

    .artist-information {
      grid-template-columns: 1fr;

      gap: 35px;
    }

    .artist-album {
      grid-template-columns:
        60px minmax(0, 1fr);

      gap: 15px;
    }

    .artist-album-image {
      width: 60px;
      height: 60px;
    }

    .artist-album-type {
      display: none;
    }

  }
</style>


<?php

/*
 * ---------------------------------------------------------
 * ARTIST DATA
 * ---------------------------------------------------------
 */

$name = $artist['name']
  ?? 'Unknown Artist';


$genres = $artist['genres']
  ?? [];


$followers = $artist['followers']['total']
  ?? 0;


$popularity = $artist['popularity']
  ?? null;


/*
 * ---------------------------------------------------------
 * SPOTIFY IMAGE
 * ---------------------------------------------------------
 */

$image = null;

if (!empty($artist['images'])) {

  $image =
    $artist['images'][0]['url']
    ?? null;

}


/*
 * ---------------------------------------------------------
 * ALBUMS
 * ---------------------------------------------------------
 */

$albums = $albums['items']
  ?? [];

?>


<div class="artist-page">


  <!-- =====================================================
       HERO
       ===================================================== -->

  <section class="artist-hero">

    <span class="artist-page-eyebrow">
      ARTIST
    </span>

    <h1 class="artist-page-title">
      <?= htmlspecialchars($name) ?>
    </h1>


    <?php if (!empty($image)): ?>

      <div class="artist-hero-image">

        <img
          src="<?= htmlspecialchars($image) ?>"
          alt="<?= htmlspecialchars($name) ?>"
        >

        <div class="artist-hero-image-overlay"></div>

      </div>

    <?php endif; ?>


    <div class="artist-hero-meta">

      <div>

        <span>FOLLOWERS</span>

        <strong>
          <?= number_format(
            $followers,
            0,
            ',',
            ' '
          ) ?>
        </strong>

      </div>


      <div>

        <span>GENRES</span>

        <strong>

          <?= htmlspecialchars(
            !empty($genres)
              ? implode(', ', $genres)
              : 'Unknown'
          ) ?>

        </strong>

      </div>


      <div>

        <span>POPULARITY</span>

        <strong>

          <?= $popularity !== null
            ? htmlspecialchars(
              (string) $popularity
            )
            : 'N/A' ?>

        </strong>

      </div>

    </div>

  </section>


  <!-- =====================================================
       INFORMATION
       ===================================================== -->

  <section class="artist-information">

    <div class="artist-description-column">

      <span class="artist-section-eyebrow">
        ABOUT THE ARTIST
      </span>

      <h2>
        <?= htmlspecialchars($name) ?>
      </h2>


      <div class="artist-description-full">

        <?php if (!empty($genres)): ?>

          <p>

            <?= htmlspecialchars(
              implode(', ', $genres)
            ) ?>

          </p>

        <?php else: ?>

          <p>
            No genre information available.
          </p>

        <?php endif; ?>

      </div>

    </div>


    <aside class="artist-facts">


      <!-- GENRES -->

      <?php if (!empty($genres)): ?>

        <div class="artist-fact">

          <span>GENRES</span>

          <div class="artist-list">

            <?php foreach ($genres as $genre): ?>

              <span>
                <?= htmlspecialchars($genre) ?>
              </span>

            <?php endforeach; ?>

          </div>

        </div>

      <?php endif; ?>


      <!-- FOLLOWERS -->

      <div class="artist-fact">

        <span>FOLLOWERS</span>

        <p>

          <?= number_format(
            $followers,
            0,
            ',',
            ' '
          ) ?>

        </p>

      </div>


      <!-- POPULARITY -->

      <?php if ($popularity !== null): ?>

        <div class="artist-fact">

          <span>POPULARITY</span>

          <p>
            <?= htmlspecialchars(
              (string) $popularity
            ) ?>
          </p>

        </div>

      <?php endif; ?>


    </aside>

  </section>


  <!-- =====================================================
       ALBUMS
       ===================================================== -->

  <section class="artist-albums">

    <div class="artist-albums-header">

      <span class="artist-section-eyebrow">
        DISCOGRAPHY
      </span>

      <h2>
        Albums
      </h2>

    </div>


    <?php if (!empty($albums)): ?>
      <?php foreach ($albums as $album): ?>

        <?php

        $albumId =
          $album['id']
          ?? null;

        $albumName =
          $album['name']
          ?? 'Untitled';

        $albumType =
          $album['album_type']
          ?? 'album';

        $albumImage = null;

        if (!empty($album['images'])) {

          $albumImage =
            $album['images'][0]['url']
            ?? null;

        }

        ?>

        <div class="artist-album">


          <?php if (!empty($albumImage)): ?>

            <a
              href="/music/album/<?= htmlspecialchars($albumId) ?>"
              class="artist-album-image"
            >

              <img
                src="<?= htmlspecialchars($albumImage) ?>"
                alt="<?= htmlspecialchars($albumName) ?>"
              >

            </a>

          <?php endif; ?>


          <a
            href="/music/album/<?= htmlspecialchars($albumId) ?>"
            class="artist-album-name"
          >

            <?= htmlspecialchars($albumName) ?>

          </a>


          <span class="artist-album-type">

            <?= htmlspecialchars(
              strtoupper($albumType)
            ) ?>

          </span>

        </div>

      <?php endforeach; ?>

    <?php else: ?>

      <p>
        No albums found.
      </p>

    <?php endif; ?>

  </section>

</div>
