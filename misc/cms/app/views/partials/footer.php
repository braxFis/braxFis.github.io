
<!-- Font Awesome -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
>

<style>

  .to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #333;
    color: white;
    padding: 12px 15px;
    border-radius: 50%;
    text-align: center;
    font-size: 20px;
    cursor: pointer;
    z-index: 1002;
    display: none;
    transition: opacity 0.3s ease, visibility 0.3s;
  }

  .to-top:hover {
    background: #555;
  }


  /* =========================================================
     FOOTER
     ========================================================= */

  .main-footer {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 40px;
  }

  .main-footer > div {
    min-width: 0;
  }

  .footer-column h1 {
    margin-top: 0;
  }

  .footer-column ul {
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .footer-column li {
    margin-bottom: 8px;
  }

  .footer-column a {
    color: inherit;
    text-decoration: none;
  }

  .footer-column a:hover {
    text-decoration: underline;
  }


  /* =========================================================
     GAME CATEGORY ROW
     ========================================================= */

  .footer-game-links {
    grid-column: 1 / -1;

    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;

    margin-top: 10px;
    padding-top: 35px;
    border-top: 1px solid rgba(128, 128, 128, 0.25);
  }

  .footer-game-group h2 {
    margin: 0 0 18px;
    font-size: 14px;
    letter-spacing: 0.08em;
  }

  .footer-game-group ul {
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .footer-game-group li {
    margin-bottom: 7px;
  }

  .footer-game-group a {
    color: inherit;
    text-decoration: none;
    font-size: 13px;
  }

  .footer-game-group a:hover {
    text-decoration: underline;
  }


  /* =========================================================
     FOOTER BOTTOM
     ========================================================= */

  .footer-bottom {
    grid-column: 1 / -1;
    margin-top: 10px;
  }


  /* =========================================================
     RESPONSIVE
     ========================================================= */

  @media (max-width: 900px) {

    .main-footer {
      grid-template-columns: repeat(2, 1fr);
    }

    .footer-game-links {
      grid-template-columns: repeat(2, 1fr);
    }

  }


  @media (max-width: 500px) {

    .main-footer {
      grid-template-columns: 1fr;
    }

    .footer-game-links {
      grid-template-columns: 1fr;
    }

  }

</style>


<!-- BACK TO TOP -->

<a href="#" class="to-top" id="toTop">
  <i class="fas fa-chevron-up"></i>
</a>


<?php

use modules\genre\models\Genre;
use modules\platform\models\Platform;
use modules\developer\models\Developer;
use modules\publisher\models\Publisher;


$genreModel = new Genre();
$platformModel = new Platform();
$developerModel = new Developer();
$publisherModel = new Publisher();

$genres = $genreModel->genres;
$platforms = $platformModel->platforms;
$developers = $developerModel->developers;
$publishers = $publisherModel->publishers;

?>


<footer class="main-footer">


  <!-- =====================================================
       EXPLORE
       ===================================================== -->

  <div class="footer-column">

    <h1>EXPLORE</h1>

    <ul>

      <li>
        <a href="/">HOME</a>
      </li>

      <li>
        <a href="/games">GAMES</a>
      </li>

      <li>
        <a href="/movies">MOVIES</a>
      </li>

      <li>
        <a href="/music">MUSIC</a>
      </li>

      <li>
        <a href="/people">PEOPLE</a>
      </li>

      <li>
        <a href="/news">NEWS</a>
      </li>

      <li>
        <a href="/reviews">REVIEWS</a>
      </li>

      <li>
        <a href="/features">FEATURES</a>
      </li>

    </ul>

  </div>


  <!-- =====================================================
       GAMES
       ===================================================== -->

  <div class="footer-column">

    <h1>GAMES</h1>

    <ul>

      <li>
        <a href="/games">
          ALL GAMES
        </a>
      </li>

    </ul>

  </div>


  <!-- =====================================================
       MOVIES
       ===================================================== -->

  <div class="footer-column">

    <h1>MOVIES</h1>

    <ul>

      <li>
        <a href="/movies">
          ALL MOVIES
        </a>
      </li>

      <li>
        <a href="/people">
          PEOPLE
        </a>
      </li>

    </ul>

  </div>


  <!-- =====================================================
       MUSIC
       ===================================================== -->

  <div class="footer-column">

    <h1>MUSIC</h1>

    <ul>

      <li>
        <a href="/music">
          MUSIC
        </a>
      </li>

      <li>
        <a href="/artists">
          ARTISTS
        </a>
      </li>

    </ul>

  </div>


  <!-- =====================================================
       GENRES / PLATFORMS / PUBLISHERS
       ===================================================== -->

  <div class="footer-game-links">


    <!-- GENRES -->

    <div class="footer-game-group">

      <h2>GENRES</h2>

      <ul>

        <?php foreach ($genres as $slug => $id): ?>

          <li>

            <a href="/genre/<?= urlencode($slug) ?>">

              <?= htmlspecialchars(
                $slug,
                ENT_QUOTES,
                'UTF-8'
              ) ?>

            </a>

          </li>

        <?php endforeach; ?>

      </ul>

    </div>


    <!-- PLATFORMS -->

    <div class="footer-game-group">

      <h2>PLATFORMS</h2>

      <ul>

        <?php foreach ($platforms as $slug => $id): ?>

          <li>

            <a href="/platform/<?= urlencode($slug) ?>">

              <?= htmlspecialchars(
                $slug,
                ENT_QUOTES,
                'UTF-8'
              ) ?>

            </a>

          </li>

        <?php endforeach; ?>

      </ul>

    </div>


    <!-- PUBLISHERS -->

    <div class="footer-game-group">

      <h2>PUBLISHERS</h2>

      <ul>

        <?php foreach ($publishers as $slug => $id): ?>

          <li>

            <a href="/publisher/<?= urlencode($slug) ?>">

              <?= htmlspecialchars(
                $slug,
                ENT_QUOTES,
                'UTF-8'
              ) ?>

            </a>

          </li>

        <?php endforeach; ?>

      </ul>

    </div>


  </div>


  <!-- =====================================================
       FOOTER BOTTOM
       ===================================================== -->

  <div class="footer-bottom">

    <p>
      &copy; <?= date('Y') ?> - COMA NEWS - All Rights Reserved
    </p>

  </div>


</footer>


<script>

  const toTop =
    document.getElementById('toTop');


  window.addEventListener('scroll', () => {

    if (window.scrollY > 300) {

      toTop.style.display = 'block';

    } else {

      toTop.style.display = 'none';

    }

  });


  toTop.addEventListener('click', (event) => {

    event.preventDefault();

    window.scrollTo({

      top: 0,
      behavior: 'smooth'

    });

  });

</script>

</body>
