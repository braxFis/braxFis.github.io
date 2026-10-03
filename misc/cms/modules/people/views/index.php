<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>People</title>

  <link
    rel="stylesheet"
    href="/misc/css/style.css"
  >

  <style>

    /* =========================================================
       COMA NEWS - PEOPLE PAGE
       ========================================================= */

    .people-page {
      width: min(1100px, 92%);

      margin: 60px auto;
    }


    /* ---------------------------------------------------------
       HEADER
       --------------------------------------------------------- */

    .people-header {
      margin-bottom: 45px;

      padding-bottom: 18px;

      border-bottom: 2px solid #111;
    }

    .people-eyebrow {
      display: block;

      margin-bottom: 8px;

      color: #888;

      font-size: 10px;
      font-weight: 700;

      letter-spacing: 3px;
    }

    .people-header h1 {
      margin: 0;

      font-size: 48px;
      line-height: 1;

      font-weight: 800;

      letter-spacing: -2px;
    }


    /* ---------------------------------------------------------
       PERSON
       --------------------------------------------------------- */

    .person {
      display: grid;

      grid-template-columns:
        280px minmax(0, 1fr);

      gap: 30px;

      padding-bottom: 35px;

      margin-bottom: 35px;

      border-bottom: 1px solid #ddd;
    }


    /* ---------------------------------------------------------
       IMAGE
       --------------------------------------------------------- */

    .person-image {
      width: 280px;
      height: 420px;

      overflow: hidden;

      background: #eee;
    }

    .person-image img {
      display: block;

      width: 100%;
      height: 100%;

      object-fit: cover;

      transition:
        transform 0.6s ease,
        filter 0.4s ease;
    }

    .person:hover
    .person-image img {
      transform: scale(1.04);

      filter: brightness(0.85);
    }


    /* ---------------------------------------------------------
       CONTENT
       --------------------------------------------------------- */

    .person-content {
      min-width: 0;
    }


    /* ---------------------------------------------------------
       TOP
       --------------------------------------------------------- */

    .person-top {
      display: flex;

      align-items: flex-start;

      justify-content: space-between;

      gap: 20px;

      margin-bottom: 25px;
    }

    .person-label {
      display: block;

      margin-bottom: 5px;

      color: #888;

      font-size: 9px;
      font-weight: 700;

      letter-spacing: 2px;
    }

    .person-title {
      margin: 0;

      color: #111;

      font-size: 28px;
      line-height: 1.05;

      font-weight: 800;

      letter-spacing: -1px;
    }

    .person-title-link {
      color: inherit;

      text-decoration: none;
    }

    .person-title-link:hover {
      text-decoration: underline;
    }


    /* ---------------------------------------------------------
       META
       --------------------------------------------------------- */

    .person-meta {
      display: flex;

      flex-wrap: wrap;

      gap: 25px;

      margin-top: 25px;

      padding-top: 18px;

      border-top: 1px solid #eee;
    }

    .person-meta-item {
      display: flex;

      flex-direction: column;

      gap: 5px;

      max-width: 220px;

      color: #444;

      font-size: 12px;

      line-height: 1.4;
    }

    .person-meta-item strong {
      color: #111;

      font-size: 16px;
    }

    .person-meta-label {
      display: block;

      color: #999;

      font-size: 8px;

      font-weight: 800;

      letter-spacing: 2px;
    }


    /* ---------------------------------------------------------
       KNOWN FOR
       --------------------------------------------------------- */

    .person-known-for {
      margin-top: 30px;

      padding-top: 18px;

      border-top: 1px solid #eee;
    }

    .person-known-for-title {
      display: block;

      margin-bottom: 12px;

      color: #999;

      font-size: 8px;

      font-weight: 800;

      letter-spacing: 2px;
    }

    .person-known-for-list {
      display: flex;

      flex-wrap: wrap;

      gap: 8px;
    }

    .person-known-for-item {
      padding: 6px 10px;

      border: 1px solid #ddd;

      color: #333;

      font-size: 10px;

      font-weight: 700;
    }


    /* ---------------------------------------------------------
       MOBILE
       --------------------------------------------------------- */

    @media (max-width: 700px) {

      .people-page {
        width: 92%;

        margin: 35px auto;
      }

      .people-header {
        margin-bottom: 30px;
      }

      .people-header h1 {
        font-size: 38px;
      }

      .person {
        grid-template-columns: 1fr;

        gap: 18px;

        padding-bottom: 30px;

        margin-bottom: 30px;
      }

      .person-image {
        width: 100%;

        height: auto;

        aspect-ratio: 2 / 3;
      }

      .person-title {
        font-size: 24px;
      }

      .person-top {
        gap: 10px;
      }

      .person-meta {
        gap: 18px;
      }

    }

  </style>

</head>


<body>


<div class="people-page">


  <!-- =====================================================
       HEADER
       ===================================================== -->

  <div class="people-header">

    <span class="people-eyebrow">
      COMA NEWS
    </span>

    <h1>
      People
    </h1>

  </div>


  <!-- =====================================================
       PEOPLE
       ===================================================== -->

  <div id="people-container">


    <?php foreach ($people as $person): ?>

      <?php

      $personName =
        $person['name']
        ?? 'Unknown Person';


      $personImage = null;

      if (!empty($person['profile_path'])) {

        $personImage =
          'https://image.tmdb.org/t/p/h632'
          . $person['profile_path'];

      }


      $department =
        $person['known_for_department']
        ?? 'N/A';


      $popularity =
        isset($person['popularity'])
          ? number_format(
          (float) $person['popularity'],
          1
        )
          : 'N/A';


      $knownFor = [];

      if (!empty($person['known_for'])) {

        foreach ($person['known_for'] as $item) {

          $title =
            $item['title']
            ?? $item['name']
            ?? '';

          if ($title) {

            $knownFor[] = $title;

          }

        }

      }

      ?>


      <article class="person">


        <!-- IMAGE -->

        <div class="person-image">

          <?php if (!empty($personImage)): ?>

            <a
              href="/people/<?= (int)$person['id'] ?>"
            >

              <img
                src="<?= htmlspecialchars($personImage) ?>"
                alt="<?= htmlspecialchars($personName) ?>"
                loading="lazy"
              >

            </a>

          <?php endif; ?>

        </div>


        <!-- CONTENT -->

        <div class="person-content">


          <!-- TOP -->

          <div class="person-top">

            <div>

              <span class="person-label">
                PERSON
              </span>


              <a
                href="/people/<?= (int)$person['id'] ?>"
                class="person-title-link"
              >

                <h2 class="person-title">

                  <?= htmlspecialchars(
                    $personName
                  ) ?>

                </h2>

              </a>

            </div>

          </div>


          <!-- META -->

          <div class="person-meta">


            <div class="person-meta-item">

              <span class="person-meta-label">
                KNOWN FOR
              </span>

              <strong>
                <?= htmlspecialchars(
                  $department
                ) ?>
              </strong>

            </div>


            <div class="person-meta-item">

              <span class="person-meta-label">
                POPULARITY
              </span>

              <strong>
                <?= htmlspecialchars(
                  $popularity
                ) ?>
              </strong>

            </div>


          </div>


          <!-- KNOWN FOR TITLES -->

          <?php if (!empty($knownFor)): ?>

            <div class="person-known-for">

              <span class="person-known-for-title">
                KNOWN FOR
              </span>


              <div class="person-known-for-list">

                <?php foreach (
                  array_slice($knownFor, 0, 5)
                  as $title
                ): ?>

                  <span
                    class="person-known-for-item"
                  >

                    <?= htmlspecialchars(
                      $title
                    ) ?>

                  </span>

                <?php endforeach; ?>

              </div>

            </div>

          <?php endif; ?>


        </div>

      </article>


    <?php endforeach; ?>


  </div>


</div>


</body>

</html>
