<?php

namespace app\widgets;

use app\models\Picture;

class PictureWidget
{
public static function renderImageSideBar($id, $page = 1)
  {
    $imageModel = new Picture();
    $screenshots = $imageModel->getScreenshots($id);

    if (empty($screenshots)) {
      return "<p class='coma-screenshots-empty'>No screenshots found</p>";
    }


    $html = "
        <section class='coma-screenshots'>

            <div class='coma-screenshots-header'>

                <div>
                    <span class='coma-screenshots-eyebrow'>
                        GALLERY
                    </span>

                    <h3 class='coma-screenshots-title'>
                        Screenshots
                    </h3>
                </div>

                <span class='coma-screenshots-count'>
                    " . count($screenshots) . "
                </span>

            </div>


            <div class='coma-screenshot-grid'>
        ";


    foreach ($screenshots as $index => $shot) {

      $src = htmlspecialchars(
        $shot['image'],
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
                    class='coma-screenshot'
                    data-image='{$src}'
                    data-index='{$index}'
                    aria-label='Open screenshot {$number}'
                >

                    <img
                        src='{$src}'
                        alt='Screenshot {$number}'
                        loading='lazy'
                    >

                    <span class='coma-screenshot-overlay'>

                        <span class='coma-screenshot-number'>
                            {$number}
                        </span>

                        <span class='coma-screenshot-expand'>
                            ↗
                        </span>

                    </span>

                </button>
            ";
    }


    $html .= "
            </div>


            <button
                type='button'
                class='coma-screenshots-load-more load-more'
                data-page='{$page}'
            >
                Visa fler
            </button>

        </section>


        <div
            class='coma-lightbox'
            id='comaLightbox'
            aria-hidden='true'
        >

            <button
                type='button'
                class='coma-lightbox-close'
                aria-label='Close'
            >
                ×
            </button>


            <button
                type='button'
                class='coma-lightbox-prev'
                aria-label='Previous screenshot'
            >
                ←
            </button>


            <div class='coma-lightbox-content'>

                <img
                    class='coma-lightbox-image'
                    src=''
                    alt=''
                >

                <div class='coma-lightbox-counter'>
                    <span class='coma-lightbox-current'>01</span>
                    /
                    <span class='coma-lightbox-total'>
                        " . count($screenshots) . "
                    </span>
                </div>

            </div>


            <button
                type='button'
                class='coma-lightbox-next'
                aria-label='Next screenshot'
            >
                →
            </button>

        </div>
        ";


    $html .= self::renderScript();

    return $html;
  }
public static function renderHomeGallery(array $games)
{
    $imageModel = new Picture();

    $screenshots = [];

    foreach ($games as $game) {

        if (empty($game['id'])) {
            continue;
        }

        $gameScreenshots = $imageModel->getScreenshots($game['id']);

        if (empty($gameScreenshots)) {
            continue;
        }

        foreach ($gameScreenshots as $shot) {

            if (empty($shot['image'])) {
                continue;
            }

            $screenshots[] = [
                'image' => $shot['image'],
                'game'  => $game['name'] ?? 'Unknown Game'
            ];
        }
    }

    if (empty($screenshots)) {
        return "<p class='coma-screenshots-empty'>No screenshots found</p>";
    }

    /*
     * Begränsa hur många screenshots Home visar.
     * Ändra exempelvis 8 till 12 om du vill.
     */
    $screenshots = array_slice($screenshots, 0, 8);

    $html = "
    <section class='coma-screenshots coma-home-screenshots'>

        <div class='coma-screenshots-header'>
            <div>
                <span class='coma-screenshots-eyebrow'>GALLERY</span>
                <h3 class='coma-screenshots-title'>Screenshots</h3>
            </div>

            <span class='coma-screenshots-count'>" . count($screenshots) . "</span>
        </div>

        <div class='coma-screenshot-grid'>
    ";

    foreach ($screenshots as $index => $shot) {

        $src = htmlspecialchars(
            $shot['image'],
            ENT_QUOTES,
            'UTF-8'
        );

        $gameName = htmlspecialchars(
            $shot['game'],
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
                class='coma-screenshot'
                data-image='{$src}'
                data-index='{$index}'
                aria-label='Open screenshot {$number}'
            >

                <img
                    src='{$src}'
                    alt='{$gameName} screenshot {$number}'
                    loading='lazy'
                >

                <span class='coma-screenshot-overlay'>
                    <span class='coma-screenshot-number'>{$number}</span>
                    <span class='coma-screenshot-expand'>↗</span>
                </span>

            </button>
        ";
    }

    $html .= "
        </div>
    </section>
    ";

   $html .= self::renderLightbox(count($screenshots));
   $html .= self::renderScript();

    return $html;
}
private static function renderLightbox($count)
  {
    return "
        <div class='coma-lightbox' id='comaHomeLightbox' aria-hidden='true'>

            <button
                type='button'
                class='coma-lightbox-close'
                aria-label='Close'
            >
                ×
            </button>

            <button
                type='button'
                class='coma-lightbox-prev'
                aria-label='Previous screenshot'
            >
                ←
            </button>

            <div class='coma-lightbox-content'>

                <img
                    class='coma-lightbox-image'
                    src=''
                    alt=''
                >

                <div class='coma-lightbox-counter'>
                    <span class='coma-lightbox-current'>01</span>
                    /
                    <span class='coma-lightbox-total'>
                        {$count}
                    </span>
                </div>

            </div>

            <button
                type='button'
                class='coma-lightbox-next'
                aria-label='Next screenshot'
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

    const screenshots =
        document.querySelectorAll(".coma-screenshot");

    const lightbox =
        document.getElementById("comaLightbox");

    if (!lightbox || screenshots.length === 0) {
        return;
    }


    const image =
        lightbox.querySelector(".coma-lightbox-image");

    const current =
        lightbox.querySelector(".coma-lightbox-current");

    const close =
        lightbox.querySelector(".coma-lightbox-close");

    const previous =
        lightbox.querySelector(".coma-lightbox-prev");

    const next =
        lightbox.querySelector(".coma-lightbox-next");


    let currentIndex = 0;


    function showImage(index) {

        if (index < 0) {
            index = screenshots.length - 1;
        }

        if (index >= screenshots.length) {
            index = 0;
        }


        currentIndex = index;


        const button = screenshots[currentIndex];

        const src =
            button.getAttribute("data-image");


        image.src = src;

        image.alt =
            "Screenshot " +
            String(currentIndex + 1).padStart(2, "0");


        current.textContent =
            String(currentIndex + 1).padStart(2, "0");

    }


    function openLightbox(index) {

        showImage(index);

        lightbox.classList.add("active");

        lightbox.setAttribute(
            "aria-hidden",
            "false"
        );

        document.body.style.overflow = "hidden";

    }


    function closeLightbox() {

        lightbox.classList.remove("active");

        lightbox.setAttribute(
            "aria-hidden",
            "true"
        );

        document.body.style.overflow = "";

    }


    screenshots.forEach(function (screenshot, index) {

        screenshot.addEventListener(
            "click",
            function () {

                openLightbox(index);

            }
        );

    });


    close.addEventListener(
        "click",
        closeLightbox
    );


    previous.addEventListener(
        "click",
        function () {

            showImage(currentIndex - 1);

        }
    );


    next.addEventListener(
        "click",
        function () {

            showImage(currentIndex + 1);

        }
    );


    lightbox.addEventListener(
        "click",
        function (event) {

            if (
                event.target === lightbox
            ) {
                closeLightbox();
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
                closeLightbox();
            }


            if (event.key === "ArrowLeft") {
                showImage(currentIndex - 1);
            }


            if (event.key === "ArrowRight") {
                showImage(currentIndex + 1);
            }

        }
    );

});
</script>

HTML;
  }
}
