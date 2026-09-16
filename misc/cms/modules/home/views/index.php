<style>
/* =========================================================
HOME - MAIN CONTENT
========================================================= */

.home-main-content {
width: 100%;
}


/* ---------------------------------------------------------
HEADER
--------------------------------------------------------- */

.home-main-header {
display: flex;
align-items: flex-end;
justify-content: space-between;

margin-bottom: 28px;
padding-bottom: 15px;

border-bottom: 2px solid #111;
}

.home-main-eyebrow {
display: block;

margin-bottom: 7px;

color: #888;

font-size: 9px;
font-weight: 800;
letter-spacing: 3px;
}

.home-main-title {
margin: 0;

color: #111;

font-size: 36px;
line-height: 1;
font-weight: 800;
letter-spacing: -1.5px;
}

.home-main-count {
color: #888;

font-size: 9px;
font-weight: 800;
letter-spacing: 2px;
}


/* ---------------------------------------------------------
GAME LIST
--------------------------------------------------------- */

.home-game-list {
display: flex;
flex-direction: column;
}


/* ---------------------------------------------------------
GAME
--------------------------------------------------------- */

.home-game {
display: grid;

grid-template-columns: 210px minmax(0, 1fr);

gap: 22px;

padding: 0 0 28px;
margin-bottom: 28px;

border-bottom: 1px solid #ddd;
}


/* ---------------------------------------------------------
IMAGE
--------------------------------------------------------- */

.home-game-image {
width: 100%;

aspect-ratio: 16 / 10;

overflow: hidden;

background: #111;
}

.home-game-image img {
display: block;

width: 100%;
height: 100%;

object-fit: cover;

transition:
transform 0.6s ease,
filter 0.4s ease;
}

.home-game:hover .home-game-image img {
transform: scale(1.05);
filter: brightness(0.82);
}


/* ---------------------------------------------------------
CONTENT
--------------------------------------------------------- */

.home-game-content {
min-width: 0;
}


/* ---------------------------------------------------------
TOP
--------------------------------------------------------- */

.home-game-top {
display: flex;
align-items: flex-start;
justify-content: space-between;

gap: 15px;

margin-bottom: 12px;
}

.home-game-label {
display: block;

margin-bottom: 5px;

color: #888;

font-size: 8px;
font-weight: 800;
letter-spacing: 2px;
}

.home-game-title {
margin: 0;

color: #111;

font-size: 24px;
line-height: 1.05;
font-weight: 800;

letter-spacing: -0.8px;
}

.home-game-release {
flex-shrink: 0;

color: #777;

font-size: 9px;
font-weight: 700;
letter-spacing: 1px;
}


/* ---------------------------------------------------------
DESCRIPTION
--------------------------------------------------------- */

.home-game-description {
display: -webkit-box;

max-width: 650px;

margin-bottom: 4px;

overflow: hidden;

color: #555;

font-size: 13px;
line-height: 1.55;

-webkit-line-clamp: 3;
-webkit-box-orient: vertical;
}

.home-game-description.expanded {
display: block;

overflow: visible;

-webkit-line-clamp: unset;
}


/* ---------------------------------------------------------
READ MORE
--------------------------------------------------------- */

.home-game-read-more {
padding: 0;

border: 0;

background: transparent;

color: #111;

font-size: 9px;
font-weight: 800;

letter-spacing: 1.5px;

cursor: pointer;

transition:
color 0.2s ease,
transform 0.2s ease;
}

.home-game-read-more:hover {
color: #777;

transform: translateX(4px);
}


/* ---------------------------------------------------------
META
--------------------------------------------------------- */

.home-game-meta {
display: flex;

flex-wrap: wrap;

gap: 20px;

margin-top: 18px;
padding-top: 13px;

border-top: 1px solid #eee;
}

.home-game-meta-item {
display: flex;

flex-direction: column;

gap: 4px;
}

.home-game-meta-item span {
color: #999;

font-size: 7px;
font-weight: 800;

letter-spacing: 1.5px;
}

.home-game-meta-item strong {
color: #222;

font-size: 12px;
font-weight: 700;
}


/* ---------------------------------------------------------
MOBILE
--------------------------------------------------------- */

@media (max-width: 700px) {

.home-main-header {
margin-bottom: 22px;
}

.home-main-title {
font-size: 30px;
}

.home-game {
grid-template-columns: 1fr;

gap: 15px;

padding-bottom: 25px;
margin-bottom: 25px;
}

.home-game-image {
aspect-ratio: 16 / 9;
}

.home-game-title {
font-size: 22px;
}

}
</style>
<div class="container home-page">

  <div class="row g-5">

    <div class="home-main-content">

      <div class="home-main-header">

        <div>
            <span class="home-main-eyebrow">
                COMA NEWS
            </span>

          <h1 class="home-main-title">
            Latest Games
          </h1>
        </div>

        <span class="home-main-count">
            <?= count($games) ?> GAMES
        </span>

      </div>


      <div class="home-game-list">

        <?php foreach ($games as $item): ?>

          <article class="home-game">

            <div class="home-game-image">

              <?php if (!empty($item['background_image'])): ?>

                <img
                  src="<?= htmlspecialchars($item['background_image']) ?>"
                  alt="<?= htmlspecialchars($item['name']) ?>"
                  loading="lazy"
                >

              <?php endif; ?>

            </div>


            <div class="home-game-content">

              <div class="home-game-top">

                <div>

                            <span class="home-game-label">
                                GAME
                            </span>

                  <a href="/games/<?= (int)$item['id'] ?>" class="game-title-link">
                    <h2 class="game-title">
                      <?= htmlspecialchars($item['name']) ?>
                    </h2>
                  </a>

                </div>

                <span class="home-game-release">
                            <?= htmlspecialchars(
                              $item['released'] ?? 'TBA'
                            ) ?>
                        </span>

              </div>


              <?php if (!empty($item['description'])): ?>

                <div class="home-game-description">
                  <?= $item['description'] ?>
                </div>

                <button
                  type="button"
                  class="home-game-read-more"
                >
                  Read More →
                </button>

              <?php endif; ?>


              <div class="home-game-meta">

                <div class="home-game-meta-item">

                  <span>RATING</span>

                  <strong>
                    <?= htmlspecialchars(
                      $item['rating'] ?? 'N/A'
                    ) ?>
                  </strong>

                </div>


                <div class="home-game-meta-item">

                  <span>METACRITIC</span>

                  <strong>
                    <?= htmlspecialchars(
                      $item['metacritic'] ?? 'N/A'
                    ) ?>
                  </strong>

                </div>


                <?php if (!empty($item['genres'])): ?>

                  <div class="home-game-meta-item">

                    <span>GENRE</span>

                    <strong>
                      <?= htmlspecialchars(
                        $item['genres'][0]['name']
                      ) ?>
                    </strong>

                  </div>

                <?php endif; ?>

              </div>

            </div>

          </article>

        <?php endforeach; ?>

      </div>

    </div>
    <aside class="col-lg-4">

      <section class="home-sidebar-section">

        <div class="home-sidebar-tabs">

          <button
            type="button"
            class="home-sidebar-tab active"
            data-tab="top10">
            Top 10
          </button>

          <button
            type="button"
            class="home-sidebar-tab"
            data-tab="upcoming">
            Upcoming
          </button>

        </div>

        <!-- TOP 10 -->

        <div id="top10" class="home-tab-content active">

          <?php foreach ($top10 as $index => $game): ?>

            <div class="top10-item">

              <span class="top10-number">
                  <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
              </span>

              <span>
                <img src="<?= htmlspecialchars($game['background_image']) ?>" width="75" height="75" style="border-radius: 100px"/>
              </span>

              <span class="top10-title">
                <a href="/games/<?= (int)$game['id'] ?>" class="game-title-link">
                    <?= htmlspecialchars($game['name']) ?>
                </a>
              </span>

            </div>

          <?php endforeach; ?>

        </div>


        <!-- UPCOMING -->

        <div id="upcoming" class="home-tab-content">

          <?php foreach ($upcoming as $game): ?>

            <div class="upcoming-item">

                    <span class="upcoming-title">
                      <a href="/games/<?= (int)$game['id'] ?>" class="game-title-link">
                        <?= htmlspecialchars($game['name']) ?>
                      </a>
                    </span>

              <span class="upcoming-date">
                        <?= htmlspecialchars($game['released'] ?? 'TBA') ?>
                    </span>

            </div>

          <?php endforeach; ?>

        </div>

      </section>

    </aside>

    <div class="screenshot-container">
      <?php
      use app\widgets\PictureWidget;
      echo (new PictureWidget)::renderHomeGallery($games);
      ?>
    </div>

    <div class="latest-videos">
      <?php
      use app\widgets\TrailerWidget;
      echo (new TrailerWidget)::renderHomeTrailers($games);
      ?>
    </div>
<script>

/* ==============================
TABS
============================== */

document.addEventListener("DOMContentLoaded", function () {

  const tabs = document.querySelectorAll(".home-sidebar-tab");
  const contents = document.querySelectorAll(".home-tab-content");


  tabs.forEach(function (tab) {

  tab.addEventListener("click", function () {

  const target = this.dataset.tab;


  // Ta bort active från alla tabs
  tabs.forEach(function (button) {
  button.classList.remove("active");
});


  // Ta bort active från allt innehåll
  contents.forEach(function (content) {
  content.classList.remove("active");
});


  // Aktivera klickad tab
  this.classList.add("active");


  // Aktivera rätt innehåll
  document.getElementById(target).classList.add("active");

});

});

});
</script>
