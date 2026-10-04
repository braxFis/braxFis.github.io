<?php

namespace app\widgets;

use app\models\MovieTrailer;
use app\models\Trailer;
use modules\game\models\Game;
class TrailerWidget
{
  public static function renderTrailers(array $games)
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

      /*
       * One trailer per game.
       */
      foreach ($gameTrailers as $trailer) {

        if (empty($trailer['max'])) {
          continue;
        }

        $trailers[] = [
          'name' => $trailer['name'] ?? 'Trailer',
          'preview' => $trailer['preview'] ?? '',
          'max' => $trailer['max'],
          'game' => $game['name'] ?? 'Unknown Game',
          'id' => (int)$game['id']
        ];

        break;
      }

      /*
       * We only need 12 games.
       */
      if (count($trailers) >= 12) {
        break;
      }
    }

    if (empty($trailers)) {
      return "<p class='coma-trailers-empty'>No trailers found</p>";
    }

    $html = "
    <section class='coma-screenshots coma-home-screenshots'>

        <div class='coma-screenshots-header'>

            <div>
                <span class='coma-screenshots-eyebrow'>
                    TRAILERS
                </span>

                <h3 class='coma-screenshots-title'>
                    Trailers
                </h3>
            </div>

            <span class='coma-screenshots-count'>
                " . count($trailers) . "
            </span>

        </div>


        <div class='coma-carousel'>

            <button
                type='button'
                class='coma-carousel-button coma-carousel-prev'
                aria-label='Previous trailers'
            >
                ←
            </button>


            <div class='coma-carousel-window'>

                <div class='coma-carousel-track'>
    ";

    foreach ($trailers as $index => $trailer) {

      $src = htmlspecialchars(
        $trailer['preview'],
        ENT_QUOTES,
        'UTF-8'
      );

      $gameName = htmlspecialchars(
        $trailer['game'],
        ENT_QUOTES,
        'UTF-8'
      );

      $trailerName = htmlspecialchars(
        $trailer['name'],
        ENT_QUOTES,
        'UTF-8'
      );

      $gameId = (int)$trailer['id'];

      $number = str_pad(
        $index + 1,
        2,
        '0',
        STR_PAD_LEFT
      );

      $html .= "
                    <a
                        href='/games/{$gameId}'
                        class='coma-carousel-item coma-trailer'
                        aria-label='View {$gameName} trailer'
                    >

                        <div class='coma-trailer-preview'>

                            <img
                                src='{$src}'
                                alt='{$gameName} trailer'
                                loading='lazy'
                            >

                            <span class='coma-trailer-play'>
                                ▶
                            </span>

                        </div>

                        <span class='coma-trailer-overlay'>

                            <span class='coma-trailer-number'>
                                {$number}
                            </span>

                            <span class='coma-trailer-name'>
                                {$trailerName}
                            </span>

                        </span>

                    </a>
        ";
    }

    $html .= "
                </div>

            </div>


            <button
                type='button'
                class='coma-carousel-button coma-carousel-next'
                aria-label='Next trailers'
            >
                →
            </button>

        </div>

    </section>
    ";


    /*
     * Homepage only needs the carousel.
     *
     * No trailer player here.
     */

    $html .= "
<script>
document.addEventListener('DOMContentLoaded', function () {

    const carousels =
        document.querySelectorAll('.coma-carousel');


    carousels.forEach(function (carousel) {

        const windowElement =
            carousel.querySelector('.coma-carousel-window');

        const track =
            carousel.querySelector('.coma-carousel-track');

        const prev =
            carousel.querySelector('.coma-carousel-prev');

        const next =
            carousel.querySelector('.coma-carousel-next');


        if (!windowElement || !track || !prev || !next) {
            return;
        }


        const getScrollAmount = function () {

            const item =
                track.querySelector('.coma-carousel-item');


            if (!item) {
                return 0;
            }


            const style =
                window.getComputedStyle(track);


            const gap =
                parseInt(style.columnGap) || 0;


            return item.offsetWidth + gap;

        };


        next.addEventListener('click', function () {

            windowElement.scrollBy({
                left: getScrollAmount(),
                behavior: 'smooth'
            });

        });


        prev.addEventListener('click', function () {

            windowElement.scrollBy({
                left: -getScrollAmount(),
                behavior: 'smooth'
            });

        });

    });

});
</script>
";

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

                <div
                    class='coma-trailer-video'
                    controls
                    playsinline
                    preload='metadata'
                >
                </div>


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

  public static function renderMovieTrailerSideBar($id)
  {
    $trailerModel = new MovieTrailer();
    $trailers = $trailerModel->getMovieTrailers($id);

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

      $max = htmlspecialchars(
        $trailer['size'],
        ENT_QUOTES,
        'UTF-8'
      );

      $number = str_pad(
        $index + 1,
        2,
        '0',
        STR_PAD_LEFT
      );

      $key = "https://www.youtube.com/embed/" . $trailer['key'];

      $html .= "
                <button
                    type='button'
                    class='coma-trailer-card'
                    data-title='{$name}'
                    data-index='{$index}'
                    data-video='{$trailer['key']}'
                >

                    <div class='coma-trailer-thumbnail'>

                   <img
                        src='https://img.youtube.com/vi/{$trailer['key']}/hqdefault.jpg'
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

                <div class='coma-trailer-video'></div>

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

  private static function renderLightbox(int $count)
  {
    return "
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

                <div
                    class='coma-trailer-video'
                    id='comaTrailerVideo'
                    style='width: 100%; aspect-ratio: 16 / 9; position: relative;'
                >
                </div>

                <div class='coma-trailer-caption'>

                    <span class='coma-trailer-current'>
                        01
                    </span>

                    <span>/</span>

                    <span class='coma-trailer-total'>
                        {$count}
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

    console.log("Trailer cards:", cards.length);
    console.log("Lightbox:", lightbox);

    if (!lightbox || cards.length === 0) {
        console.log("Trailer script stopped");
        return;
    }
console.log("EVENT LISTENERS START");

cards.forEach(function (card, index) {

    console.log("REGISTER CARD:", index);

    card.addEventListener(
        "click",
        function () {

            console.log("CARD CLICK:", index);

            openTrailer(index);

        }
    );

});

    const videoContainer =
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

      console.log(
    "PLAYER:",
    videoContainer.offsetWidth,
    videoContainer.offsetHeight
);

console.log(
    "LIGHTBOX:",
    lightbox.offsetWidth,
    lightbox.offsetHeight
);
        if (index < 0) {
            index = cards.length - 1;
        }

        if (index >= cards.length) {
            index = 0;
        }


        currentIndex = index;


        const card =
            cards[currentIndex];


        const videoId =
            card.getAttribute("data-video");

        console.log("VIDEO ID:", videoId);

        const trailerTitle =
            card.getAttribute("data-title");


        /*
         * Remove previous YouTube player
         */
 videoContainer.innerHTML = "";

const iframe = document.createElement("iframe");

iframe.src =
    "https://www.youtube.com/embed/"
    + videoId
    + "?autoplay="
    + (autoplay ? "1" : "0")
    + "&rel=0";

iframe.style.position = "absolute";
iframe.style.top = "0";
iframe.style.left = "0";
iframe.style.width = "100%";
iframe.style.height = "100%";
iframe.style.display = "block";
iframe.style.border = "0";

iframe.setAttribute(
    "allow",
    "autoplay; encrypted-media; picture-in-picture"
);

iframe.setAttribute("allowfullscreen", "");

videoContainer.appendChild(iframe);

console.log("VIDEO CONTAINER HTML:");
console.log(videoContainer.outerHTML);

console.log("IFRAME PARENT:");
console.log(iframe.parentElement);

console.log("IFRAME STYLE ATTRIBUTE:");
console.log(iframe.getAttribute("style"));

// TESTA EFTER ATT BROWSER HAR LAYOUTAT ELEMENTET
requestAnimationFrame(function () {

    console.log(
        "PLAYER:",
        videoContainer.offsetWidth,
        videoContainer.offsetHeight
    );

    console.log(
        "IFRAME:",
        iframe.offsetWidth,
        iframe.offsetHeight
    );

    console.log(
        "IFRAME RECT:",
        iframe.getBoundingClientRect()
    );

});       /*
         * Caption
         */
        current.textContent =
            String(currentIndex + 1).padStart(2, "0");


        title.textContent =
            trailerTitle;

    }


function openTrailer(index) {

    lightbox.classList.add("active");
    lightbox.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";

    showTrailer(index, true);
}

    function closeTrailer() {

        /*
         * Completely remove YouTube iframe.
         *
         * This stops the video and unloads YouTube.
         */
        videoContainer.innerHTML = "";


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

            console.log("CARD CLICK:", index);

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
