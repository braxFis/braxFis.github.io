<?php

namespace app\widgets;

use app\models\Trailer;

class TrailerWidget
{

public static function renderHomeTrailers(array $games)
{
    $trailerModel = new Trailer();

    $trailers = [];

    foreach ($games as $game) {

        if (empty($game['id'])) {
            continue;
        }

        $gameTrailers = $trailerModel->getTrailers($game['id']);

        if (empty($gameTrailers)) {
            continue;
        }

        foreach ($gameTrailers as $trailer) {

            if (empty($trailer['max'])) {
                continue;
            }

            $trailers[] = [
                'name'    => $trailer['name'] ?? 'Trailer',
                'preview' => $trailer['preview'] ?? '',
                'max'     => $trailer['max'],
                'game'    => $game['name'] ?? 'Unknown Game'
            ];
        }
    }

    if (empty($trailers)) {
        return "<p class='coma-trailers-empty'>No trailers found</p>";
    }

    /*
     * Begränsa antalet trailers på Home.
     */
    $trailers = array_slice($trailers, 0, 6);

    $html = "
    <section class='coma-trailers coma-home-trailers'>

        <div class='coma-trailers-header'>
            <div>
                <span class='coma-trailers-eyebrow'>VIDEO</span>
                <h3 class='coma-trailers-title'>Trailers</h3>
            </div>

            <span class='coma-trailers-count'>" . count($trailers) . "</span>
        </div>

        <div class='coma-trailer-grid'>
    ";

    foreach ($trailers as $index => $trailer) {

        $name = htmlspecialchars(
            $trailer['name'],
            ENT_QUOTES,
            'UTF-8'
        );

        $preview = htmlspecialchars(
            $trailer['preview'],
            ENT_QUOTES,
            'UTF-8'
        );

        $max = htmlspecialchars(
            $trailer['max'],
            ENT_QUOTES,
            'UTF-8'
        );

        $gameName = htmlspecialchars(
            $trailer['game'],
            ENT_QUOTES,
            'UTF-8'
        );

        $number = str_pad(
            $index + 1,
            2,
            '0',
            STR_PAD_LEFT
        );

        $html .= "
            <button
                type='button'
                class='coma-trailer-card'
                data-video='{$max}'
                data-preview='{$preview}'
                data-title='{$name}'
                data-game='{$gameName}'
                data-index='{$index}'
            >

                <div class='coma-trailer-thumbnail'>

                    <img
                        src='{$preview}'
                        alt='{$gameName} - {$name}'
                        loading='lazy'
                    >

                    <div class='coma-trailer-overlay'></div>

                    <span class='coma-trailer-number'>
                        {$number}
                    </span>

                    <span class='coma-trailer-play'>
                        <span>▶</span>
                    </span>

                    <span class='coma-trailer-expand'>
                        ↗
                    </span>

                </div>

                <div class='coma-trailer-info'>

                    <span class='coma-trailer-label'>
                        {$gameName}
                    </span>

                    <span class='coma-trailer-name'>
                        {$name}
                    </span>

                </div>

            </button>
        ";
    }

    $html .= "
        </div>
    </section>
    ";

  $html .= self::renderLightbox(count($trailers));
  $html .= self::renderScript();

  return $html;
}

  public static function renderTrailerSideBar($id)
  {
    $trailerModel = new Trailer();
    $trailers = $trailerModel->getTrailers($id);

    if (empty($trailers)) {
      return "<p class='coma-trailers-empty'>No trailers found</p>";
    }


    $html = "
        <section class='coma-trailers'>

            <div class='coma-trailers-header'>

                <div>
                    <span class='coma-trailers-eyebrow'>
                        VIDEO
                    </span>

                    <h3 class='coma-trailers-title'>
                        Trailers
                    </h3>
                </div>

                <span class='coma-trailers-count'>
                    " . count($trailers) . "
                </span>

            </div>


            <div class='coma-trailer-grid'>
        ";


    foreach ($trailers as $index => $trailer) {

      $name = htmlspecialchars(
        $trailer['name'],
        ENT_QUOTES,
        'UTF-8'
      );

      $preview = htmlspecialchars(
        $trailer['preview'],
        ENT_QUOTES,
        'UTF-8'
      );

      $max = htmlspecialchars(
        $trailer['max'],
        ENT_QUOTES,
        'UTF-8'
      );

      $number = str_pad(
        $index + 1,
        2,
        '0',
        STR_PAD_LEFT
      );


      $html .= "
                <button
                    type='button'
                    class='coma-trailer-card'
                    data-video='{$max}'
                    data-preview='{$preview}'
                    data-title='{$name}'
                    data-index='{$index}'
                >

                    <div class='coma-trailer-thumbnail'>

                        <img
                            src='{$preview}'
                            alt='{$name}'
                            loading='lazy'
                        >


                        <div class='coma-trailer-overlay'></div>


                        <span class='coma-trailer-number'>
                            {$number}
                        </span>


                        <span class='coma-trailer-play'>
                            <span>▶</span>
                        </span>


                        <span class='coma-trailer-expand'>
                            ↗
                        </span>

                    </div>


                    <div class='coma-trailer-info'>

                        <span class='coma-trailer-label'>
                            TRAILER {$number}
                        </span>

                        <span class='coma-trailer-name'>
                            {$name}
                        </span>

                    </div>

                </button>
            ";
    }


    $html .= "
            </div>

        </section>


        <div
            class='coma-trailer-lightbox'
            id='comaTrailerLightbox'
            aria-hidden='true'
        >

            <button
                type='button'
                class='coma-trailer-close'
                aria-label='Close'
            >
                ×
            </button>


            <button
                type='button'
                class='coma-trailer-prev'
                aria-label='Previous trailer'
            >
                ←
            </button>


            <div class='coma-trailer-player'>

                <video
                    class='coma-trailer-video'
                    controls
                    playsinline
                    preload='metadata'
                >
                </video>


                <div class='coma-trailer-caption'>

                    <span class='coma-trailer-current'>
                        01
                    </span>

                    <span>/</span>

                    <span class='coma-trailer-total'>
                        " . count($trailers) . "
                    </span>

                    <span class='coma-trailer-caption-title'></span>

                </div>

            </div>


            <button
                type='button'
                class='coma-trailer-next'
                aria-label='Next trailer'
            >
                →
            </button>

        </div>
        ";


    $html .= self::renderScript();

    return $html;
  }


  private static function renderScript()
  {
    return <<<'HTML'

<script>
document.addEventListener("DOMContentLoaded", function () {

    const cards =
        document.querySelectorAll(".coma-trailer-card");

    const lightbox =
        document.getElementById("comaTrailerLightbox");

    if (!lightbox || cards.length === 0) {
        return;
    }


    const video =
        lightbox.querySelector(".coma-trailer-video");

    const current =
        lightbox.querySelector(".coma-trailer-current");

    const title =
        lightbox.querySelector(".coma-trailer-caption-title");

    const close =
        lightbox.querySelector(".coma-trailer-close");

    const previous =
        lightbox.querySelector(".coma-trailer-prev");

    const next =
        lightbox.querySelector(".coma-trailer-next");


    let currentIndex = 0;


    function showTrailer(index, autoplay = true) {

        if (index < 0) {
            index = cards.length - 1;
        }

        if (index >= cards.length) {
            index = 0;
        }


        currentIndex = index;


        const card = cards[currentIndex];


        const src =
            card.getAttribute("data-video");


        const trailerTitle =
            card.getAttribute("data-title");


        video.pause();

        video.src = src;

        video.load();


        current.textContent =
            String(currentIndex + 1).padStart(2, "0");


        title.textContent =
            trailerTitle;


        if (autoplay) {

            video.play().catch(function () {
                // Browser may block autoplay.
            });

        }

    }


    function openTrailer(index) {

        showTrailer(index, true);


        lightbox.classList.add("active");

        lightbox.setAttribute(
            "aria-hidden",
            "false"
        );


        document.body.style.overflow = "hidden";

    }


    function closeTrailer() {

        video.pause();

        video.removeAttribute("src");

        video.load();


        lightbox.classList.remove("active");

        lightbox.setAttribute(
            "aria-hidden",
            "true"
        );


        document.body.style.overflow = "";

    }


    cards.forEach(function (card, index) {

        card.addEventListener(
            "click",
            function () {

                openTrailer(index);

            }
        );

    });


    close.addEventListener(
        "click",
        closeTrailer
    );


    previous.addEventListener(
        "click",
        function () {

            showTrailer(
                currentIndex - 1,
                true
            );

        }
    );


    next.addEventListener(
        "click",
        function () {

            showTrailer(
                currentIndex + 1,
                true
            );

        }
    );


    lightbox.addEventListener(
        "click",
        function (event) {

            if (
                event.target === lightbox
            ) {
                closeTrailer();
            }

        }
    );


    document.addEventListener(
        "keydown",
        function (event) {

            if (
                !lightbox.classList.contains("active")
            ) {
                return;
            }


            if (event.key === "Escape") {

                closeTrailer();

            }


            if (event.key === "ArrowLeft") {

                showTrailer(
                    currentIndex - 1,
                    true
                );

            }


            if (event.key === "ArrowRight") {

                showTrailer(
                    currentIndex + 1,
                    true
                );

            }

        }
    );

});
</script>

HTML;
  }
}
