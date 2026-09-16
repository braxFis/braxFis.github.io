<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Games</title>
  <link rel="stylesheet" href="/misc/css/style.css">
  <style>
      /* =========================================================
         COMA NEWS - GAMES PAGE
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
       GAME
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
      width: 100%;
      aspect-ratio: 16 / 10;
      overflow: hidden;
      background: #111;
    }

    .game-image img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;

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
       STORES
       --------------------------------------------------------- */

    .game-stores {
      margin-top: 20px;
    }

    .game-store-links {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;

      margin-top: 8px;
    }

    .game-store-links a {
      padding: 6px 10px;

      border: 1px solid #ddd;

      color: #333;

      font-size: 10px;
      font-weight: 700;

      text-decoration: none;

      transition:
        background 0.2s ease,
        color 0.2s ease,
        border-color 0.2s ease;
    }

    .game-store-links a:hover {
      background: #111;
      border-color: #111;
      color: #fff;
    }


    /* ---------------------------------------------------------
       ESRB
       --------------------------------------------------------- */

    .game-esrb {
      display: flex;
      align-items: center;
      gap: 10px;

      margin-top: 18px;

      color: #444;
      font-size: 11px;
    }


    /* ---------------------------------------------------------
       LOAD MORE
       --------------------------------------------------------- */

    .games-load-more {
      display: flex;
      justify-content: center;

      margin-top: 50px;
    }

    .games-load-more button {
      padding: 13px 30px;

      border: 1px solid #111;
      background: #111;

      color: #fff;

      font-size: 10px;
      font-weight: 800;
      letter-spacing: 2px;

      cursor: pointer;

      transition:
        background 0.25s ease,
        color 0.25s ease;
    }

    .games-load-more button:hover {
      background: #fff;
      color: #111;
    }

    .games-load-more button:disabled {
      opacity: 0.4;
      cursor: not-allowed;
    }


    /* =========================================================
       MOBILE
       ========================================================= */

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

      .game-image {
        aspect-ratio: 16 / 9;
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
    <span class="games-eyebrow">COMA NEWS</span>
    <h1>Games</h1>
  </div>

  <div id="game-container">

    <?php foreach ($games as $item): ?>

      <article class="game">

        <div class="game-image">

          <?php if (!empty($item['background_image'])): ?>

            <img
              src="<?= htmlspecialchars($item['background_image']) ?>"
              alt="<?= htmlspecialchars($item['name']) ?>"
              loading="lazy"
            >

          <?php endif; ?>

        </div>


        <div class="game-content">

          <div class="game-top">

            <div>
              <span class="game-label">GAME</span>

              <a href="/games/<?= (int)$item['id'] ?>" class="game-title-link">
                <h2 class="game-title">
                  <?= htmlspecialchars($item['name']) ?>
                </h2>
              </a>
            </div>

            <span class="game-release">
                            <?= htmlspecialchars($item['released'] ?? 'TBA') ?>
                        </span>

          </div>


          <div class="game-description">

            <?= $item['description'] ?>

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
                                RATING
                            </span>

              <strong>
                <?= htmlspecialchars($item['rating'] ?? 'N/A') ?>
              </strong>

            </div>


            <div class="game-meta-item">

                            <span class="game-meta-label">
                                METACRITIC
                            </span>

              <strong>
                <?= htmlspecialchars($item['metacritic'] ?? 'N/A') ?>
              </strong>

            </div>


            <div class="game-meta-item">

                            <span class="game-meta-label">
                                GENRE
                            </span>

              <span>
                                <?= implode(
                                  ", ",
                                  array_map(
                                    fn($g) => htmlspecialchars($g['name']),
                                    $item['genres'] ?? []
                                  )
                                ) ?>
                            </span>

            </div>


            <div class="game-meta-item">

                            <span class="game-meta-label">
                                PLATFORMS
                            </span>

              <span>
                                <?= implode(
                                  ", ",
                                  array_map(
                                    fn($p) => htmlspecialchars($p['platform']['name']),
                                    $item['platforms'] ?? []
                                  )
                                ) ?>
                            </span>

            </div>

          </div>


          <?php if (!empty($item['stores'])): ?>

            <div class="game-stores">

                            <span class="game-meta-label">
                                AVAILABLE AT
                            </span>

              <div class="game-store-links">

                <?php foreach ($item['stores'] as $store): ?>

                  <a
                    href="https://<?= htmlspecialchars($store['store']['domain']) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    <?= htmlspecialchars($store['store']['name']) ?>
                  </a>

                <?php endforeach; ?>

              </div>

            </div>

          <?php endif; ?>


          <div class="game-esrb">

                        <span class="game-meta-label">
                            ESRB
                        </span>

            <span>
                            <?= htmlspecialchars(
                              $item['esrb_rating']['name'] ?? 'Not Rated'
                            ) ?>
                        </span>

          </div>

        </div>

      </article>

    <?php endforeach; ?>

  </div>


  <div class="games-load-more">

    <button
      type="button"
      id="loadMoreBtn"
      data-page="1"
    >
      Load More
    </button>

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


    /*
     * LOAD MORE
     */

    const btn =
      document.getElementById("loadMoreBtn");

    const container =
      document.getElementById("game-container");


    if (!btn || !container) {
      return;
    }


    btn.addEventListener("click", async () => {

      const currentPage =
        parseInt(btn.dataset.page);

      const nextPage =
        currentPage + 1;


      try {

        const response =
          await fetch(
            `/games/loadMore?page=${nextPage}`
          );


        if (!response.ok) {
          throw new Error("Request failed");
        }


        const games =
          await response.json();


        if (!games || games.length === 0) {

          btn.disabled = true;
          btn.textContent = "No more games";

          return;
        }


        games.forEach(game => {

          const block =
            document.createElement("article");

          block.className = "game";


          const description =
            game.description || "";


          block.innerHTML = `

                    <div class="game-image">

                        ${
            game.background_image
              ?
              `
                            <img
                                src="${game.background_image}"
                                alt="${game.name}"
                                loading="lazy"
                            >
                            `
              :
              ""
          }

                    </div>


                    <div class="game-content">

                        <div class="game-top">

                            <div>

                                <span class="game-label">
                                    GAME
                                </span>

                                <h2 class="game-title">
                                    ${game.name}
                                </h2>

                            </div>

                            <span class="game-release">
                                ${game.released || "TBA"}
                            </span>

                        </div>


                        <div class="game-description">

                            ${description}

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
                                    RATING
                                </span>

                                <strong>
                                    ${game.rating || "N/A"}
                                </strong>

                            </div>


                            <div class="game-meta-item">

                                <span class="game-meta-label">
                                    METACRITIC
                                </span>

                                <strong>
                                    ${game.metacritic || "N/A"}
                                </strong>

                            </div>

                        </div>

                    </div>
                `;


          container.appendChild(block);

        });


        btn.dataset.page =
          nextPage;

      } catch (error) {

        console.error(error);

      }

    });

  });

</script>

</body>
</html>
