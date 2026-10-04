<?php

namespace app\widgets;

class GalleryWidget
{
  /**
   * Render the Coma News editorial gallery rotator.
   *
   * The widget handles:
   * - Data
   * - HTML
   * - CSS
   * - JavaScript
   */
  public static function render(array $items, int $limit = 6): string
  {
    $gallery = [];

    foreach ($items as $item) {

      if (
        empty($item['id']) ||
        empty($item['image'])
      ) {
        continue;
      }

      $gallery[] = [
        'id'       => (int) $item['id'],
        'image'    => $item['image'],
        'title'    => $item['title'] ?? '',
        'subtitle' => $item['subtitle'] ?? '',
        'url'      => $item['url'] ?? null
      ];

      if (count($gallery) >= $limit) {
        break;
      }
    }


    /*
     * No valid gallery items.
     */
    if (empty($gallery)) {
      return '';
    }


    /*
     * Build HTML.
     */
    $html = "
    <section class='coma-gallery-rotator'>

        <div class='coma-gallery-window'>

            <div class='coma-gallery-track'>
    ";


    foreach ($gallery as $index => $item) {

      $image = htmlspecialchars(
        $item['image'],
        ENT_QUOTES,
        'UTF-8'
      );

      $title = htmlspecialchars(
        $item['title'],
        ENT_QUOTES,
        'UTF-8'
      );

      $subtitle = htmlspecialchars(
        $item['subtitle'],
        ENT_QUOTES,
        'UTF-8'
      );

      $url = $item['url'];

      if (empty($url)) {
        $url = '/games/' . $item['id'];
      }

      $url = htmlspecialchars(
        $url,
        ENT_QUOTES,
        'UTF-8'
      );

      $number = str_pad(
        $index + 1,
        2,
        '0',
        STR_PAD_LEFT
      );

      $active = $index === 0
        ? ' is-active'
        : '';


      $html .= "
                <a
                    href='{$url}'
                    class='coma-gallery-slide{$active}'
                    data-gallery-slide='{$index}'
                >

                    <img
                        src='{$image}'
                        alt='{$title}'
                        class='coma-gallery-image'
                        loading='" . ($index === 0 ? 'eager' : 'lazy') . "'
                    >

                    <div class='coma-gallery-overlay'>

                        <div class='coma-gallery-content'>

                            <span class='coma-gallery-number'>
                                {$number}
                            </span>

                            <div class='coma-gallery-text'>

                                <span class='coma-gallery-subtitle'>
                                    {$subtitle}
                                </span>

                                <h2 class='coma-gallery-title'>
                                    {$title}
                                </h2>

                            </div>

                        </div>

                    </div>

                </a>
      ";
    }


    $total = str_pad(
      count($gallery),
      2,
      '0',
      STR_PAD_LEFT
    );


    $html .= "
            </div>

        </div>


        <button
            type='button'
            class='coma-gallery-button coma-gallery-prev'
            aria-label='Previous slide'
        >
            ←
        </button>


        <button
            type='button'
            class='coma-gallery-button coma-gallery-next'
            aria-label='Next slide'
        >
            →
        </button>


        <div class='coma-gallery-controls'>

            <span class='coma-gallery-current'>
                01
            </span>

            <span class='coma-gallery-divider'>
                /
            </span>

            <span class='coma-gallery-total'>
                {$total}
            </span>

        </div>


        <div class='coma-gallery-progress'>
    ";


    foreach ($gallery as $index => $item) {

      $active = $index === 0
        ? ' is-active'
        : '';

      $html .= "
            <button
                type='button'
                class='coma-gallery-progress-item{$active}'
                data-gallery-target='{$index}'
                aria-label='Go to slide " . ($index + 1) . "'
            ></button>
      ";
    }


    $html .= "
        </div>

    </section>

<style>

    /* =====================================================
       COMA NEWS - GALLERY ROTATOR
       ===================================================== */

    .coma-gallery-rotator {
        position: relative;
        width: 100%;
        height: min(680px, 70vh);
        min-height: 480px;
        overflow: hidden;
        background: #111;
    }


    .coma-gallery-window {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }


    .coma-gallery-track {
        position: relative;
        width: 100%;
        height: 100%;
    }


    /* -----------------------------------------------------
       SLIDES
       ----------------------------------------------------- */

    .coma-gallery-slide {
        position: absolute;
        inset: 0;

        display: block;

        width: 100%;
        height: 100%;

        opacity: 0;
        visibility: hidden;

        overflow: hidden;

        text-decoration: none;
        color: inherit;

        transition:
            opacity 0.7s ease,
            visibility 0.7s ease;
    }


    .coma-gallery-slide.is-active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
    }


    /* -----------------------------------------------------
       IMAGE
       ----------------------------------------------------- */

    .coma-gallery-image {
        position: absolute;
        inset: 0;

        width: 100%;
        height: 100%;

        object-fit: cover;

        transform: scale(1.03);

        transition:
            transform 6s ease;
    }


    .coma-gallery-slide.is-active
    .coma-gallery-image {
        transform: scale(1.08);
    }


    /* -----------------------------------------------------
       DARK OVERLAY
       ----------------------------------------------------- */

    .coma-gallery-overlay {
        position: absolute;
        inset: 0;

        display: flex;
        align-items: flex-end;

        background:
            linear-gradient(
                to top,
                rgba(0, 0, 0, 0.90) 0%,
                rgba(0, 0, 0, 0.55) 35%,
                rgba(0, 0, 0, 0.12) 70%,
                rgba(0, 0, 0, 0.05) 100%
            );
    }


    /* -----------------------------------------------------
       CONTENT
       ----------------------------------------------------- */

    .coma-gallery-content {
        display: flex;
        align-items: flex-end;

        width: min(1200px, 88%);

        margin:
            0 auto
            70px;

        gap: 28px;
    }


    /* -----------------------------------------------------
       NUMBER
       ----------------------------------------------------- */

    .coma-gallery-number {
        flex: 0 0 auto;

        font-size: 13px;
        font-weight: 700;

        letter-spacing: 0.18em;

        opacity: 0.7;
    }


    /* -----------------------------------------------------
       TITLE BOX
       ----------------------------------------------------- */

    .coma-gallery-text {
        max-width: 760px;

        padding: 18px 24px;

        background: rgba(0, 0, 0, 0.92);

        color: #fff;
    }


    /* -----------------------------------------------------
       SUBTITLE
       ----------------------------------------------------- */

    .coma-gallery-subtitle {
        display: block;

        margin-bottom: 8px;

        font-size: 12px;
        font-weight: 700;

        letter-spacing: 0.20em;
        text-transform: uppercase;

        color: #fff;

        opacity: 0.65;
    }


    /* -----------------------------------------------------
       TITLE
       ----------------------------------------------------- */

    .coma-gallery-title {
        margin: 0;

        font-size: clamp(
            36px,
            5vw,
            76px
        );

        line-height: 0.95;

        font-weight: 800;
        letter-spacing: -0.04em;

        text-transform: uppercase;

        color: #fff;
    }


    /* -----------------------------------------------------
       ARROWS
       ----------------------------------------------------- */

    .coma-gallery-button {
        position: absolute;

        top: 50%;

        z-index: 10;

        width: 52px;
        height: 52px;

        margin-top: -26px;

        border: 1px solid rgba(255,255,255,0.35);

        background: rgba(0,0,0,0.20);

        color: #fff;

        font-size: 22px;

        cursor: pointer;

        display: flex;
        align-items: center;
        justify-content: center;

        transition:
            background 0.2s ease,
            color 0.2s ease,
            border-color 0.2s ease;
    }


    .coma-gallery-button:hover {
        background: #fff;
        color: #000;
        border-color: #fff;
    }


    .coma-gallery-prev {
        left: 28px;
    }


    .coma-gallery-next {
        right: 28px;
    }


    /* -----------------------------------------------------
       COUNTER
       ----------------------------------------------------- */

    .coma-gallery-controls {
        position: absolute;

        right: 30px;
        bottom: 70px;

        z-index: 10;

        display: flex;
        align-items: center;

        gap: 8px;

        font-size: 12px;
        font-weight: 700;

        letter-spacing: 0.15em;
    }


    .coma-gallery-current {
        opacity: 1;
    }


    .coma-gallery-total {
        opacity: 0.45;
    }


    .coma-gallery-divider {
        opacity: 0.35;
    }


    /* -----------------------------------------------------
       PROGRESS
       ----------------------------------------------------- */

    .coma-gallery-progress {
        position: absolute;

        left: 50%;
        bottom: 28px;

        z-index: 10;

        transform: translateX(-50%);

        display: flex;

        gap: 7px;
    }


    .coma-gallery-progress-item {
        width: 32px;
        height: 2px;

        padding: 0;

        border: 0;

        background: rgba(255,255,255,0.35);

        cursor: pointer;

        transition:
            width 0.3s ease,
            background 0.3s ease;
    }


    .coma-gallery-progress-item.is-active {
        width: 52px;
        background: #fff;
    }


    /* -----------------------------------------------------
       RESPONSIVE
       ----------------------------------------------------- */

    @media (max-width: 900px) {

        .coma-gallery-rotator {
            height: 60vh;
            min-height: 430px;
        }


        .coma-gallery-content {
            width: calc(100% - 120px);
            margin-bottom: 70px;
        }


        .coma-gallery-title {
            font-size: clamp(
                34px,
                7vw,
                60px
            );
        }


        .coma-gallery-prev {
            left: 16px;
        }


        .coma-gallery-next {
            right: 16px;
        }

    }


    @media (max-width: 600px) {

        .coma-gallery-rotator {
            height: 70vh;
            min-height: 420px;
        }


        .coma-gallery-content {
            width: calc(100% - 48px);

            margin:
                0 24px
                72px;

            gap: 16px;
        }


        .coma-gallery-number {
            display: none;
        }


        .coma-gallery-text {
            max-width: 100%;

            padding: 14px 18px;
        }


        .coma-gallery-title {
            font-size: clamp(
                32px,
                11vw,
                52px
            );
        }


        .coma-gallery-subtitle {
            font-size: 10px;
        }


        .coma-gallery-button {
            width: 42px;
            height: 42px;

            margin-top: -21px;

            font-size: 18px;
        }


        .coma-gallery-prev {
            left: 12px;
        }


        .coma-gallery-next {
            right: 12px;
        }


        .coma-gallery-controls {
            right: 24px;
            bottom: 28px;
        }


        .coma-gallery-progress {
            left: 24px;
            bottom: 30px;

            transform: none;
        }


        .coma-gallery-progress-item {
            width: 22px;
        }


        .coma-gallery-progress-item.is-active {
            width: 38px;
        }

    }

</style>


    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const rotators =
                    document.querySelectorAll(
                        '.coma-gallery-rotator'
                    );


                rotators.forEach(function (rotator) {

                    const slides =
                        rotator.querySelectorAll(
                            '.coma-gallery-slide'
                        );

                    const prev =
                        rotator.querySelector(
                            '.coma-gallery-prev'
                        );

                    const next =
                        rotator.querySelector(
                            '.coma-gallery-next'
                        );

                    const current =
                        rotator.querySelector(
                            '.coma-gallery-current'
                        );

                    const progress =
                        rotator.querySelectorAll(
                            '.coma-gallery-progress-item'
                        );


                    if (!slides.length) {
                        return;
                    }


                    let activeIndex = 0;


                    const update = function (index) {

                        if (
                            index < 0 ||
                            index >= slides.length
                        ) {
                            return;
                        }


                        slides.forEach(
                            function (slide, slideIndex) {

                                slide.classList.toggle(
                                    'is-active',
                                    slideIndex === index
                                );

                            }
                        );


                        progress.forEach(
                            function (item, itemIndex) {

                                item.classList.toggle(
                                    'is-active',
                                    itemIndex === index
                                );

                            }
                        );


                        current.textContent =
                            String(index + 1).padStart(2, '0');


                        activeIndex = index;

                    };


                    const nextSlide = function () {

                        const nextIndex =
                            activeIndex + 1 >= slides.length
                                ? 0
                                : activeIndex + 1;

                        update(nextIndex);

                    };


                    const previousSlide = function () {

                        const previousIndex =
                            activeIndex - 1 < 0
                                ? slides.length - 1
                                : activeIndex - 1;

                        update(previousIndex);

                    };


                    next.addEventListener(
                        'click',
                        nextSlide
                    );


                    prev.addEventListener(
                        'click',
                        previousSlide
                    );


                    progress.forEach(
                        function (item, index) {

                            item.addEventListener(
                                'click',
                                function () {
                                    update(index);
                                }
                            );

                        }
                    );


                    /*
                     * Automatic rotation.
                     */
                    let autoplay =
                        setInterval(
                            nextSlide,
                            7000
                        );


                    /*
                     * Pause while mouse is over
                     * the gallery.
                     */
                    rotator.addEventListener(
                        'mouseenter',
                        function () {
                            clearInterval(autoplay);
                        }
                    );


                    rotator.addEventListener(
                        'mouseleave',
                        function () {

                            autoplay =
                                setInterval(
                                    nextSlide,
                                    7000
                                );

                        }
                    );


                    /*
                     * Keyboard navigation.
                     */
                    rotator.addEventListener(
                        'keydown',
                        function (event) {

                            if (event.key === 'ArrowLeft') {
                                previousSlide();
                            }

                            if (event.key === 'ArrowRight') {
                                nextSlide();
                            }

                        }
                    );


                    update(0);

                });

            }
        );

    </script>
    ";


    return $html;
  }
}
