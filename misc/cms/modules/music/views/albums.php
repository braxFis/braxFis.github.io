<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Albums</title>

  <link rel="stylesheet" href="/misc/css/style.css">

  <style>

    /* =========================================================
       COMA NEWS - ALBUMS PAGE
       ========================================================= */

    .games-page {
      width: min(1100px, 92%);
      margin: 60px auto;
    }


    /* ---------------------------------------------------------
       HEADER
       --------------------------------------------------------- */

    .games-header {
      margin-bottom: 45px;
      padding-bottom: 18px;
      border-bottom: 2px solid #111;
    }

    .games-eyebrow {
      display: block;
      margin-bottom: 8px;
      color: #888;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 3px;
    }

    .games-header h1 {
      margin: 0;
      font-size: 48px;
      line-height: 1;
      font-weight: 800;
      letter-spacing: -2px;
    }


    /* ---------------------------------------------------------
       ALBUM
       --------------------------------------------------------- */

    .game {
      display: grid;
      grid-template-columns: 280px minmax(0, 1fr);
      gap: 30px;

      padding: 0 0 35px;
      margin-bottom: 35px;

      border-bottom: 1px solid #ddd;
    }


    /* ---------------------------------------------------------
       IMAGE
       --------------------------------------------------------- */

    .game-image {
      width: 280px;
      height: 420px;
      aspect-ratio: 5 / 3;
      overflow: visible;
      background: transparent;
    }

    .game-image img {
      display: block;
      width: 280px;
      height: 420px;
      object-fit: contain;

      transition:
        transform 0.6s ease,
        filter 0.4s ease;
    }

    .game:hover .game-image img {
      transform: scale(1.04);
      filter: brightness(0.85);
    }


    /* ---------------------------------------------------------
       CONTENT
       --------------------------------------------------------- */

    .game-content {
      min-width: 0;
    }


    /* ---------------------------------------------------------
       TOP
       --------------------------------------------------------- */

    .game-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 20px;

      margin-bottom: 15px;
    }

    .game-label {
      display: block;
      margin-bottom: 5px;

      color: #888;

      font-size: 9px;
      font-weight: 700;
      letter-spacing: 2px;
    }

    .game-title {
      margin: 0;

      color: #111;

      font-size: 28px;
      line-height: 1.05;
      font-weight: 800;
      letter-spacing: -1px;
    }

    .game-title-link {
      color: inherit;
      text-decoration: none;
    }

    .game-release {
      flex-shrink: 0;

      color: #777;

      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1px;
    }


    /* ---------------------------------------------------------
       DESCRIPTION
       --------------------------------------------------------- */

    .game-description {
      position: relative;

      max-width: 750px;

      margin-bottom: 5px;

      font-size: 15px;
      line-height: 1.65;
      color: #444;

      display: -webkit-box;
      -webkit-line-clamp: 4;
      -webkit-box-orient: vertical;

      overflow: hidden;
    }

    .game-description.expanded {
      display: block;
      -webkit-line-clamp: unset;
      overflow: visible;
    }


    /* ---------------------------------------------------------
       READ MORE
       --------------------------------------------------------- */

    .game-read-more {
      padding: 0;

      border: 0;
      background: transparent;

      color: #111;

      font-size: 10px;
      font-weight: 800;
      letter-spacing: 1.5px;

      cursor: pointer;

      transition:
        color 0.2s ease,
        transform 0.2s ease;
    }

    .game-read-more:hover {
      color: #777;
      transform: translateX(4px);
    }


    /* ---------------------------------------------------------
       META
       --------------------------------------------------------- */

    .game-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 25px;

      margin-top: 25px;
      padding-top: 18px;

      border-top: 1px solid #eee;
    }

    .game-meta-item {
      display: flex;
      flex-direction: column;
      gap: 5px;

      max-width: 220px;

      color: #444;
      font-size: 12px;
      line-height: 1.4;
    }

    .game-meta-item strong {
      color: #111;
      font-size: 16px;
    }


    /* ---------------------------------------------------------
       META LABEL
       --------------------------------------------------------- */

    .game-meta-label {
      display: block;

      color: #999;

      font-size: 8px;
      font-weight: 800;
      letter-spacing: 2px;
    }


    /* ---------------------------------------------------------
       MOBILE
       --------------------------------------------------------- */

    @media (max-width: 700px) {

      .games-page {
        width: 92%;
        margin: 35px auto;
      }

      .games-header {
        margin-bottom: 30px;
      }

      .games-header h1 {
        font-size: 38px;
      }

      .game {
        grid-template-columns: 1fr;
        gap: 18px;
        padding-bottom: 30px;
        margin-bottom: 30px;
      }

      .game-title {
        font-size: 24px;
      }

      .game-top {
        gap: 10px;
      }

      .game-release {
        font-size: 9px;
      }

      .game-description {
        font-size: 14px;
        -webkit-line-clamp: 4;
      }

      .game-meta {
        gap: 18px;
      }

    }

  </style>
</head>


<body>

<div class="games-page">


  <div class="games-header">

    <span class="games-eyebrow">
      COMA NEWS
    </span>

    <h1>
      Albums
    </h1>

  </div>


  <div id="game-container">


    <?php foreach ($albums as $item): ?>

      <article class="game">


        <?php
        $image = $item['images'][0]['url'] ?? null;
        ?>


        <div class="game-image">

          <?php if ($image): ?>

            <img
              src="<?= htmlspecialchars($image) ?>"
              alt="<?= htmlspecialchars($item['name']) ?>"
              loading="lazy"
            >

          <?php endif; ?>

        </div>


        <div class="game-content">


          <div class="game-top">

            <div>

              <span class="game-label">
                ALBUM
              </span>


              <a
                href="/music/<?= htmlspecialchars($item['id']) ?>"
                class="game-title-link"
              >

                <h2 class="game-title">

                  <?= htmlspecialchars($item['name']) ?>

                </h2>

              </a>

            </div>


            <span class="game-release">

              <?= htmlspecialchars(
                $item['release_date'] ?? 'TBA'
              ) ?>

            </span>

          </div>


          <div class="game-description">

            <?= htmlspecialchars(
              ucfirst($item['album_type'] ?? 'Album')
            ) ?>

          </div>


          <button
            type="button"
            class="game-read-more"
          >
            Read More
          </button>


          <div class="game-meta">


            <div class="game-meta-item">

              <span class="game-meta-label">
                TYPE
              </span>

              <strong>

                <?= htmlspecialchars(
                  ucfirst($item['album_type'] ?? 'N/A')
                ) ?>

              </strong>

            </div>


            <div class="game-meta-item">

              <span class="game-meta-label">
                TRACKS
              </span>

              <strong>

                <?= htmlspecialchars(
                  $item['total_tracks'] ?? 'N/A'
                ) ?>

              </strong>

            </div>


            <div class="game-meta-item">

              <span class="game-meta-label">
                ARTIST
              </span>

              <span>

                <?php foreach ($item['artists'] ?? [] as $artist): ?>

                  <?= htmlspecialchars(
                    $artist['name'] ?? 'Unknown'
                  ) ?>

                <?php endforeach; ?>

              </span>

            </div>


          </div>


          <div class="game-esrb">

            <span class="game-meta-label">
              RELEASE
            </span>

            <span>

              <?= htmlspecialchars(
                $item['release_date'] ?? 'Unknown'
              ) ?>

            </span>

          </div>


        </div>

      </article>

    <?php endforeach; ?>


  </div>


</div>


<script>

  document.addEventListener("DOMContentLoaded", () => {

    /*
     * READ MORE
     */

    document.addEventListener("click", (event) => {

      if (!event.target.classList.contains("game-read-more")) {
        return;
      }

      const button = event.target;

      const description =
        button.previousElementSibling;

      description.classList.toggle("expanded");

      if (description.classList.contains("expanded")) {

        button.textContent = "Read Less";

      } else {

        button.textContent = "Read More";

      }

    });

  });

</script>

</body>
</html>
