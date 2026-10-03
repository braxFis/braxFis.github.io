<style>
  /* =========================================================
     COMA NEWS - PERSON DETAIL
     ========================================================= */

  .person-page {
    width: min(1100px, 92%);
    margin: 60px auto 100px;
  }


  /* ---------------------------------------------------------
     HERO
     --------------------------------------------------------- */

  .person-hero {
    margin-bottom: 55px;
  }

  .person-hero-top {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;

    gap: 30px;

    margin-bottom: 22px;
  }

  .person-page-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .person-page-title {
    margin: 0;

    color: #111;

    font-size: clamp(40px, 6vw, 72px);

    line-height: .95;

    font-weight: 800;

    letter-spacing: -3px;
  }


  /* ---------------------------------------------------------
     PROFILE IMAGE
     --------------------------------------------------------- */

  .person-hero-image {
    position: relative;

    width: 100%;

    aspect-ratio: 21 / 9;

    overflow: hidden;

    background: #111;
  }

  .person-hero-image img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;
  }


  /* ---------------------------------------------------------
     HERO META
     --------------------------------------------------------- */

  .person-hero-meta {
    display: grid;

    grid-template-columns:
      repeat(3, 1fr);

    border-bottom: 1px solid #ddd;
  }

  .person-hero-meta > div {
    padding: 18px 20px;

    border-right: 1px solid #ddd;
  }

  .person-hero-meta > div:first-child {
    padding-left: 0;
  }

  .person-hero-meta > div:last-child {
    border-right: 0;
  }

  .person-hero-meta span {
    display: block;

    margin-bottom: 5px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .person-hero-meta strong {
    color: #111;

    font-size: 14px;
  }


  /* ---------------------------------------------------------
     INFORMATION
     --------------------------------------------------------- */

  .person-information {
    display: grid;

    grid-template-columns:
      minmax(0, 1fr) 260px;

    gap: 60px;

    margin-bottom: 80px;
  }

  .person-section-eyebrow {
    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 3px;
  }

  .person-description-column h2 {
    margin: 0 0 20px;

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -1px;
  }

  .person-description-full {
    color: #444;

    font-size: 15px;
    line-height: 1.75;
  }

  .person-description-full p {
    margin-top: 0;
  }


  /* ---------------------------------------------------------
     FACTS
     --------------------------------------------------------- */

  .person-facts {
    border-top: 2px solid #111;
  }

  .person-fact {
    padding: 16px 0;

    border-bottom: 1px solid #ddd;
  }

  .person-fact > span {
    display: block;

    margin-bottom: 7px;

    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }

  .person-fact p {
    margin: 0;

    color: #333;

    font-size: 12px;
    line-height: 1.5;
  }


  /* ---------------------------------------------------------
     FILMOGRAPHY
     --------------------------------------------------------- */

  .person-filmography {
    margin-top: 40px;
  }

  .person-filmography-header {
    display: flex;

    align-items: flex-end;
    justify-content: space-between;

    margin-bottom: 25px;

    padding-bottom: 15px;

    border-bottom: 2px solid #111;
  }

  .person-filmography-header h2 {
    margin: 0;

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -1px;
  }

  .person-filmography-header span {
    color: #999;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: 2px;
  }


  /* ---------------------------------------------------------
     MOVIE GRID
     --------------------------------------------------------- */

  .person-movie-grid {
    display: grid;

    grid-template-columns:
      repeat(5, 1fr);

    gap: 20px;
  }

  .person-movie {
    display: block;

    color: inherit;

    text-decoration: none;
  }

  .person-movie-poster {
    width: 100%;

    aspect-ratio: 2 / 3;

    overflow: hidden;

    background: #eee;

    margin-bottom: 10px;
  }

  .person-movie-poster img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;

    transition: transform .25s ease;
  }

  .person-movie:hover
  .person-movie-poster img {
    transform: scale(1.03);
  }

  .person-movie-title {
    margin: 0;

    color: #111;

    font-size: 13px;
    font-weight: 800;

    line-height: 1.25;
  }

  .person-movie-year {
    margin-top: 4px;

    color: #999;

    font-size: 10px;
    font-weight: 700;
  }


  /* ---------------------------------------------------------
     MOBILE
     --------------------------------------------------------- */

  @media (max-width: 900px) {

    .person-movie-grid {
      grid-template-columns:
        repeat(4, 1fr);
    }

  }


  @media (max-width: 700px) {

    .person-page {
      margin-top: 35px;
    }

    .person-page-title {
      font-size: 42px;

      letter-spacing: -2px;
    }

    .person-hero-image {
      aspect-ratio: 4 / 5;
    }

    .person-hero-meta {
      grid-template-columns: 1fr;
    }

    .person-hero-meta > div,
    .person-hero-meta > div:first-child {
      padding: 14px 0;

      border-right: 0;
      border-bottom: 1px solid #ddd;
    }

    .person-information {
      grid-template-columns: 1fr;

      gap: 35px;
    }

    .person-movie-grid {
      grid-template-columns:
        repeat(2, 1fr);

      gap: 15px;
    }

  }
</style>


<?php

/*
 * ---------------------------------------------------------
 * PERSON DATA
 * ---------------------------------------------------------
 */

$name = $person['name']
  ?? 'Unknown Person';


$biography = $person['biography']
  ?? '';


$birthday = $person['birthday']
  ?? '';


$placeOfBirth = $person['place_of_birth']
  ?? '';


$knownForDepartment = $person['known_for_department']
  ?? '';


$deathday = $person['deathday']
  ?? '';


/*
 * ---------------------------------------------------------
 * TMDB PROFILE IMAGE
 * ---------------------------------------------------------
 */

$profileImage = null;

if (!empty($person['profile_path'])) {

  $profileImage =
    'https://image.tmdb.org/t/p/h632'
    . $person['profile_path'];

}


/*
 * ---------------------------------------------------------
 * MOVIE COUNT
 * ---------------------------------------------------------
 */

$movieCount = count($movies ?? []);

?>


<div class="person-page">


  <!-- =====================================================
       HERO
       ===================================================== -->

  <section class="person-hero">

    <div class="person-hero-top">

      <div>

        <span class="person-page-eyebrow">
          PERSON
        </span>

        <h1 class="person-page-title">
          <?= htmlspecialchars($name) ?>
        </h1>

      </div>

    </div>


    <?php if (!empty($profileImage)): ?>

      <div class="person-hero-image">

        <img
          src="<?= htmlspecialchars($profileImage) ?>"
          alt="<?= htmlspecialchars($name) ?>"
        >

      </div>

    <?php endif; ?>


    <div class="person-hero-meta">

      <div>

        <span>BIRTHDAY</span>

        <strong>
          <?= htmlspecialchars(
            $birthday ?: 'N/A'
          ) ?>
        </strong>

      </div>


      <div>

        <span>PLACE OF BIRTH</span>

        <strong>
          <?= htmlspecialchars(
            $placeOfBirth ?: 'N/A'
          ) ?>
        </strong>

      </div>


      <div>

        <span>KNOWN FOR</span>

        <strong>
          <?= htmlspecialchars(
            $knownForDepartment ?: 'N/A'
          ) ?>
        </strong>

      </div>

    </div>

  </section>


  <!-- =====================================================
       INFORMATION
       ===================================================== -->

  <section class="person-information">


    <div class="person-description-column">

      <span class="person-section-eyebrow">
        ABOUT THE PERSON
      </span>

      <h2>
        <?= htmlspecialchars($name) ?>
      </h2>

      <div class="person-description-full">

        <?php if (!empty($biography)): ?>

          <p>
            <?= nl2br(
              htmlspecialchars($biography)
            ) ?>
          </p>

        <?php else: ?>

          <p>
            No biography available.
          </p>

        <?php endif; ?>

      </div>

    </div>


    <aside class="person-facts">


      <!-- DEPARTMENT -->

      <?php if (!empty($knownForDepartment)): ?>

        <div class="person-fact">

          <span>DEPARTMENT</span>

          <p>
            <?= htmlspecialchars(
              $knownForDepartment
            ) ?>
          </p>

        </div>

      <?php endif; ?>


      <!-- BIRTHDAY -->

      <?php if (!empty($birthday)): ?>

        <div class="person-fact">

          <span>BORN</span>

          <p>
            <?= htmlspecialchars(
              $birthday
            ) ?>
          </p>

        </div>

      <?php endif; ?>


      <!-- PLACE OF BIRTH -->

      <?php if (!empty($placeOfBirth)): ?>

        <div class="person-fact">

          <span>PLACE OF BIRTH</span>

          <p>
            <?= htmlspecialchars(
              $placeOfBirth
            ) ?>
          </p>

        </div>

      <?php endif; ?>


      <!-- DEATH -->

      <?php if (!empty($deathday)): ?>

        <div class="person-fact">

          <span>DIED</span>

          <p>
            <?= htmlspecialchars(
              $deathday
            ) ?>
          </p>

        </div>

      <?php endif; ?>


    </aside>

  </section>


  <!-- =====================================================
       FILMOGRAPHY
       ===================================================== -->

  <?php if (!empty($movies)): ?>

    <section class="person-filmography">

      <div class="person-filmography-header">

        <h2>
          Filmography
        </h2>

        <span>
          <?= $movieCount ?> MOVIES
        </span>

      </div>


      <div class="person-movie-grid">

        <?php foreach ($movies as $movie): ?>

          <?php

          $movieTitle =
            $movie['title']
            ?? $movie['original_title']
            ?? 'Untitled';

          $movieYear = '';

          if (!empty($movie['release_date'])) {

            $movieYear =
              substr(
                $movie['release_date'],
                0,
                4
              );

          }

          $poster = null;

          if (!empty($movie['poster_path'])) {

            $poster =
              'https://image.tmdb.org/t/p/w500'
              . $movie['poster_path'];

          }

          ?>

          <a
            class="person-movie"
            href="/movies/<?= (int)$movie['id'] ?>"
          >

            <div class="person-movie-poster">

              <?php if (!empty($poster)): ?>

                <img
                  src="<?= htmlspecialchars($poster) ?>"
                  alt="<?= htmlspecialchars($movieTitle) ?>"
                >

              <?php else: ?>

                <div></div>

              <?php endif; ?>

            </div>


            <h3 class="person-movie-title">
              <?= htmlspecialchars($movieTitle) ?>
            </h3>


            <?php if (!empty($movieYear)): ?>

              <div class="person-movie-year">
                <?= htmlspecialchars($movieYear) ?>
              </div>

            <?php endif; ?>

          </a>

        <?php endforeach; ?>

      </div>

    </section>

  <?php endif; ?>


</div>
