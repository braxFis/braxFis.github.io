<head>
<!-- Add to your <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!--<link rel="stylesheet" href="../../../css/side.css">-->
<style>
    body {
        margin: 0;
        font-family: sans-serif;
    }
    /* =========================================================
       COMA NEWS TRAILERS
       ========================================================= */

    .coma-trailers {
      margin-top: 30px;
    }


    /* HEADER */

    .coma-trailers-header {
      display: flex;

      align-items: flex-end;

      justify-content: space-between;

      margin-bottom: 18px;

      padding-bottom: 12px;

      border-bottom: 1px solid #222;
    }


    .coma-trailers-eyebrow {
      display: block;

      margin-bottom: 4px;

      color: #888;

      font-size: 9px;

      font-weight: 700;

      letter-spacing: 3px;
    }


    .coma-trailers-title {
      margin: 0;

      font-size: 25px;

      font-weight: 800;

      letter-spacing: -1px;
    }


    .coma-trailers-count {
      color: #888;

      font-size: 11px;

      font-weight: 700;

      letter-spacing: 1px;
    }


    /* =========================================================
       TRAILER GRID
       ========================================================= */

    .coma-trailer-grid {
      display: grid;

      grid-template-columns:
        repeat(2, minmax(0, 1fr));

      gap: 18px 10px;
    }


    /* CARD */

    .coma-trailer-card {
      display: block;

      width: 100%;

      padding: 0;

      border: 0;

      background: transparent;

      color: #111;

      text-align: left;

      cursor: pointer;
    }


    /* THUMBNAIL */

    .coma-trailer-thumbnail {
      position: relative;

      width: 100%;

      aspect-ratio: 16 / 9;

      overflow: hidden;

      background: #111;
    }


    .coma-trailer-thumbnail img {
      display: block;

      width: 100%;
      height: 100%;

      object-fit: cover;

      transition:
        transform 0.7s ease,
        filter 0.5s ease;
    }


    .coma-trailer-card:hover
    .coma-trailer-thumbnail img {

      transform: scale(1.06);

      filter: brightness(0.6);
    }


    /* DARK OVERLAY */

    .coma-trailer-overlay {
      position: absolute;

      inset: 0;

      background:
        linear-gradient(
          to top,
          rgba(0,0,0,0.7),
          transparent 55%
        );

      opacity: 0.7;

      transition:
        opacity 0.3s ease;
    }


    .coma-trailer-card:hover
    .coma-trailer-overlay {

      opacity: 1;
    }


    /* NUMBER */

    .coma-trailer-number {
      position: absolute;

      left: 12px;

      top: 10px;

      color: rgba(255,255,255,0.8);

      font-size: 10px;

      font-weight: 700;

      letter-spacing: 1px;
    }


    /* PLAY BUTTON */

    .coma-trailer-play {
      position: absolute;

      left: 50%;
      top: 50%;

      transform:
        translate(-50%, -50%)
        scale(0.9);

      display: flex;

      align-items: center;
      justify-content: center;

      width: 58px;
      height: 58px;

      border: 1px solid rgba(255,255,255,0.75);

      border-radius: 50%;

      background: rgba(0,0,0,0.25);

      color: #fff;

      transition:
        transform 0.35s ease,
        background 0.35s ease;
    }


    .coma-trailer-play span {
      margin-left: 3px;

      font-size: 15px;
    }


    .coma-trailer-card:hover
    .coma-trailer-play {

      transform:
        translate(-50%, -50%)
        scale(1.08);

      background: #fff;

      color: #111;
    }


    /* EXPAND */

    .coma-trailer-expand {
      position: absolute;

      right: 12px;

      bottom: 10px;

      display: flex;

      align-items: center;
      justify-content: center;

      width: 30px;
      height: 30px;

      border: 1px solid rgba(255,255,255,0.45);

      border-radius: 50%;

      color: #fff;

      font-size: 14px;

      opacity: 0;

      transform: translateY(5px);

      transition:
        opacity 0.3s ease,
        transform 0.3s ease;
    }


    .coma-trailer-card:hover
    .coma-trailer-expand {

      opacity: 1;

      transform: translateY(0);
    }


    /* INFO */

    .coma-trailer-info {
      display: flex;

      flex-direction: column;

      gap: 5px;

      padding-top: 10px;
    }


    .coma-trailer-label {
      color: #888;

      font-size: 8px;

      font-weight: 700;

      letter-spacing: 2px;
    }


    .coma-trailer-name {
      font-size: 14px;

      font-weight: 700;

      line-height: 1.2;

      transition:
        transform 0.3s ease;
    }


    .coma-trailer-card:hover
    .coma-trailer-name {

      transform: translateX(5px);
    }


    /* =========================================================
       FULLSCREEN TRAILER
       ========================================================= */

    .coma-trailer-lightbox {
      position: fixed;

      inset: 0;

      z-index: 10000;

      display: flex;

      align-items: center;
      justify-content: center;

      padding: 40px;

      background: rgba(0,0,0,0.96);

      opacity: 0;

      visibility: hidden;

      transition:
        opacity 0.3s ease,
        visibility 0.3s ease;
    }


    .coma-trailer-lightbox.active {

      opacity: 1;

      visibility: visible;
    }


    /* VIDEO */

    .coma-trailer-player {
      position: relative;

      width: min(1200px, 88vw);
    }


    .coma-trailer-video {
      display: block;

      width: 100%;

      max-height: 82vh;

      background: #000;

      box-shadow:
        0 30px 100px rgba(0,0,0,0.8);
    }


    /* CLOSE */

    .coma-trailer-close {
      position: absolute;

      top: 25px;

      right: 30px;

      z-index: 5;

      width: 45px;

      height: 45px;

      border: 1px solid rgba(255,255,255,0.4);

      border-radius: 50%;

      background: transparent;

      color: #fff;

      font-size: 28px;

      font-weight: 300;

      cursor: pointer;

      transition:
        background 0.25s ease,
        color 0.25s ease,
        transform 0.25s ease;
    }


    .coma-trailer-close:hover {

      background: #fff;

      color: #111;

      transform: rotate(90deg);
    }


    /* PREVIOUS / NEXT */

    .coma-trailer-prev,
    .coma-trailer-next {

      position: absolute;

      top: 50%;

      transform: translateY(-50%);

      width: 55px;

      height: 55px;

      border: 1px solid rgba(255,255,255,0.35);

      border-radius: 50%;

      background: transparent;

      color: #fff;

      font-size: 20px;

      cursor: pointer;

      transition:
        background 0.25s ease,
        color 0.25s ease;
    }


    .coma-trailer-prev {
      left: 30px;
    }


    .coma-trailer-next {
      right: 30px;
    }


    .coma-trailer-prev:hover,
    .coma-trailer-next:hover {

      background: #fff;

      color: #111;
    }


    /* CAPTION */

    .coma-trailer-caption {

      display: flex;

      align-items: center;

      gap: 8px;

      margin-top: 12px;

      color: #777;

      font-size: 10px;

      font-weight: 700;

      letter-spacing: 2px;
    }


    .coma-trailer-current {
      color: #fff;
    }


    .coma-trailer-caption-title {
      margin-left: 15px;

      color: #aaa;

      font-weight: 500;

      letter-spacing: 1px;
    }


    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 600px) {

      .coma-trailer-grid {

        grid-template-columns: 1fr;

      }


      .coma-trailer-lightbox {

        padding: 15px;

      }


      .coma-trailer-player {

        width: 95vw;

      }


      .coma-trailer-prev,
      .coma-trailer-next {

        width: 42px;

        height: 42px;

      }


      .coma-trailer-prev {
        left: 10px;
      }


      .coma-trailer-next {
        right: 10px;
      }


      .coma-trailer-close {

        top: 15px;

        right: 15px;

      }

    }
    /* =========================================================
   COMA NEWS SCREENSHOTS
   ========================================================= */

    .coma-screenshots {
      margin-top: 30px;
    }


    /* HEADER */

    .coma-screenshots-header {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;

      margin-bottom: 18px;

      padding-bottom: 12px;

      border-bottom: 1px solid #222;
    }


    .coma-screenshots-eyebrow {
      display: block;

      margin-bottom: 4px;

      color: #888;

      font-size: 9px;
      font-weight: 700;

      letter-spacing: 3px;
    }


    .coma-screenshots-title {
      margin: 0;

      font-size: 25px;
      font-weight: 800;

      letter-spacing: -1px;
    }


    .coma-screenshots-count {
      color: #888;

      font-size: 11px;
      font-weight: 700;

      letter-spacing: 1px;
    }


    /* GRID */

    .coma-screenshot-grid {
      display: grid;

      grid-template-columns:
        repeat(2, minmax(0, 1fr));

      gap: 8px;
    }


    /* SCREENSHOT */

    .coma-screenshot {
      position: relative;

      display: block;

      width: 100%;

      padding: 0;

      border: 0;

      background: #111;

      cursor: pointer;

      overflow: hidden;

      aspect-ratio: 16 / 10;
    }


    .coma-screenshot img {
      display: block;

      width: 100%;
      height: 100%;

      object-fit: cover;

      transition:
        transform 0.6s ease,
        filter 0.4s ease;
    }


    .coma-screenshot:hover img {
      transform: scale(1.06);

      filter: brightness(0.65);
    }


    /* OVERLAY */

    .coma-screenshot-overlay {
      position: absolute;

      inset: 0;

      display: flex;

      align-items: flex-end;

      justify-content: space-between;

      padding: 12px;

      background:
        linear-gradient(
          to top,
          rgba(0,0,0,0.65),
          transparent 45%
        );

      opacity: 0;

      transition: opacity 0.3s ease;
    }


    .coma-screenshot:hover .coma-screenshot-overlay {
      opacity: 1;
    }


    .coma-screenshot-number {
      color: #fff;

      font-size: 10px;
      font-weight: 700;

      letter-spacing: 1px;
    }


    .coma-screenshot-expand {
      display: flex;

      align-items: center;
      justify-content: center;

      width: 30px;
      height: 30px;

      border: 1px solid rgba(255,255,255,0.5);

      border-radius: 50%;

      color: #fff;

      font-size: 15px;
    }


    /* LOAD MORE */

    .coma-screenshots-load-more {
      width: 100%;

      margin-top: 12px;

      padding: 12px;

      border: 1px solid #222;

      background: transparent;

      color: #222;

      font-size: 10px;
      font-weight: 700;

      letter-spacing: 2px;

      cursor: pointer;

      transition:
        background 0.25s ease,
        color 0.25s ease;
    }


    .coma-screenshots-load-more:hover {
      background: #111;

      color: #fff;
    }


    /* =========================================================
       LIGHTBOX
       ========================================================= */

    .coma-lightbox {
      position: fixed;

      inset: 0;

      z-index: 10000;

      display: flex;

      align-items: center;
      justify-content: center;

      padding: 50px;

      background: rgba(0,0,0,0.94);

      opacity: 0;

      visibility: hidden;

      transition:
        opacity 0.3s ease,
        visibility 0.3s ease;
    }


    .coma-lightbox.active {
      opacity: 1;

      visibility: visible;
    }


    .coma-lightbox-content {
      position: relative;

      max-width: 90vw;
      max-height: 90vh;
    }


    .coma-lightbox-image {
      display: block;

      max-width: 90vw;
      max-height: 85vh;

      width: auto;
      height: auto;

      object-fit: contain;

      box-shadow:
        0 30px 80px rgba(0,0,0,0.7);
    }


    /* CLOSE */

    .coma-lightbox-close {
      position: absolute;

      top: 25px;
      right: 30px;

      z-index: 3;

      width: 45px;
      height: 45px;

      border: 1px solid rgba(255,255,255,0.4);

      border-radius: 50%;

      background: transparent;

      color: #fff;

      font-size: 28px;
      font-weight: 300;

      cursor: pointer;

      transition:
        background 0.25s ease,
        color 0.25s ease,
        transform 0.25s ease;
    }


    .coma-lightbox-close:hover {
      background: #fff;

      color: #111;

      transform: rotate(90deg);
    }


    /* PREVIOUS / NEXT */

    .coma-lightbox-prev,
    .coma-lightbox-next {
      position: absolute;

      top: 50%;

      transform: translateY(-50%);

      width: 55px;
      height: 55px;

      border: 1px solid rgba(255,255,255,0.35);

      border-radius: 50%;

      background: transparent;

      color: #fff;

      font-size: 20px;

      cursor: pointer;

      transition:
        background 0.25s ease,
        color 0.25s ease;
    }


    .coma-lightbox-prev {
      left: 30px;
    }


    .coma-lightbox-next {
      right: 30px;
    }


    .coma-lightbox-prev:hover,
    .coma-lightbox-next:hover {
      background: #fff;

      color: #111;
    }


    /* COUNTER */

    .coma-lightbox-counter {
      position: absolute;

      left: 0;
      bottom: -30px;

      color: rgba(255,255,255,0.65);

      font-size: 10px;
      font-weight: 700;

      letter-spacing: 2px;
    }


    /* MOBILE */

    @media (max-width: 600px) {

      .coma-screenshot-grid {
        grid-template-columns: 1fr;
      }


      .coma-lightbox {
        padding: 20px;
      }


      .coma-lightbox-image {
        max-width: 95vw;
        max-height: 80vh;
      }


      .coma-lightbox-prev,
      .coma-lightbox-next {
        width: 42px;
        height: 42px;
      }


      .coma-lightbox-prev {
        left: 10px;
      }


      .coma-lightbox-next {
        right: 10px;
      }


      .coma-lightbox-close {
        top: 15px;
        right: 15px;
      }

    }
    /* =========================================================
   COMA NEWS HEADER
   ========================================================= */

    .coma-header {
      position: relative;
      z-index: 1000;

      width: 100%;
      height: 80px;

      display: flex;
      align-items: center;
      justify-content: space-between;

      padding: 0 40px;

      background: #ffffff;
      border-bottom: 1px solid #e5e5e5;
    }


    /* LOGO */

    .coma-logo {
      display: flex;
      align-items: baseline;
      gap: 5px;

      color: #111111;
      text-decoration: none;

      font-size: 25px;
      font-weight: 900;
      letter-spacing: -1.5px;
    }

    .coma-logo span {
      font-weight: 400;
      letter-spacing: -1px;
    }


    /* MENU BUTTON */

    .coma-menu-toggle {
      display: flex;
      align-items: center;
      gap: 14px;

      border: 0;
      background: none;

      color: #111111;

      cursor: pointer;
      padding: 10px;

      font-size: 12px;
      font-weight: 700;
      letter-spacing: 2px;
    }

    .coma-menu-icon {
      width: 28px;

      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .coma-menu-icon i {
      display: block;

      width: 100%;
      height: 2px;

      background: #111111;

      transition:
        transform 0.3s ease,
        width 0.3s ease;
    }

    .coma-menu-toggle:hover .coma-menu-icon i:nth-child(2) {
      width: 65%;
    }

    .coma-menu-toggle:hover .coma-menu-icon i:nth-child(3) {
      width: 80%;
    }


    /* =========================================================
       FULLSCREEN MENU
       ========================================================= */

    .coma-menu-overlay {
      position: fixed;

      inset: 0;

      z-index: 9999;

      background: #0b0b0b;

      color: #ffffff;

      visibility: hidden;
      opacity: 0;

      transition:
        opacity 0.45s ease,
        visibility 0.45s ease;

      overflow: hidden;
    }


    .coma-menu-overlay.active {
      visibility: visible;
      opacity: 1;
    }


    /* BACKGROUND */

    .coma-menu-background {
      position: absolute;

      inset: 0;

      background:
        radial-gradient(
          circle at 80% 20%,
          rgba(255,255,255,0.08),
          transparent 30%
        );

      pointer-events: none;
    }


    /* CLOSE */

    .coma-menu-close {
      position: absolute;

      top: 30px;
      right: 40px;

      z-index: 10;

      display: flex;
      align-items: center;
      gap: 12px;

      border: 0;
      background: transparent;

      color: #ffffff;

      cursor: pointer;

      font-size: 11px;
      font-weight: 700;
      letter-spacing: 2px;
    }

    .coma-menu-close strong {
      display: flex;
      align-items: center;
      justify-content: center;

      width: 42px;
      height: 42px;

      border: 1px solid rgba(255,255,255,0.4);
      border-radius: 50%;

      font-size: 27px;
      font-weight: 300;

      transition:
        background 0.3s ease,
        color 0.3s ease,
        transform 0.3s ease;
    }

    .coma-menu-close:hover strong {
      background: #ffffff;
      color: #111111;
      transform: rotate(90deg);
    }


    /* =========================================================
       MENU CONTENT
       ========================================================= */

    .coma-menu-inner {
      position: relative;

      z-index: 2;

      width: 100%;
      height: 100%;

      display: grid;

      grid-template-columns: 1.4fr 0.8fr;

      gap: 80px;

      padding: 120px 8vw 100px;
    }


    /* NAVIGATION */

    .coma-menu-navigation {
      display: flex;
      flex-direction: column;

      justify-content: center;
    }


    .coma-menu-eyebrow {
      margin-bottom: 30px;

      color: #777777;

      font-size: 11px;
      font-weight: 700;

      letter-spacing: 4px;
    }


    .coma-menu-navigation nav {
      display: flex;
      flex-direction: column;
    }


    /* MENU LINK */

    .coma-menu-link {
      position: relative;

      display: grid;

      grid-template-columns: 60px 1fr 40px;

      align-items: center;

      width: 100%;

      padding: 15px 0;

      border-bottom: 1px solid rgba(255,255,255,0.12);

      color: #ffffff;

      text-decoration: none;

      overflow: hidden;

      transition: padding 0.35s ease;
    }


    .coma-menu-link::before {
      content: "";

      position: absolute;

      left: 0;
      bottom: 0;

      width: 0;
      height: 1px;

      background: #ffffff;

      transition: width 0.4s ease;
    }


    .coma-menu-link:hover {
      padding-left: 20px;
    }


    .coma-menu-link:hover::before {
      width: 100%;
    }


    .coma-menu-number {
      color: #666666;

      font-size: 11px;
      font-weight: 600;

      letter-spacing: 1px;

      transition: color 0.3s ease;
    }


    .coma-menu-link:hover .coma-menu-number {
      color: #ffffff;
    }


    .coma-menu-title {
      font-size: clamp(38px, 5vw, 82px);

      font-weight: 800;

      line-height: 0.95;

      letter-spacing: -3px;

      transition:
        transform 0.4s ease,
        letter-spacing 0.4s ease;
    }


    .coma-menu-link:hover .coma-menu-title {
      transform: translateX(8px);

      letter-spacing: -1px;
    }


    .coma-menu-arrow {
      opacity: 0;

      font-size: 25px;

      transform: translate(-10px, 10px);

      transition:
        opacity 0.3s ease,
        transform 0.3s ease;
    }


    .coma-menu-link:hover .coma-menu-arrow {
      opacity: 1;

      transform: translate(0, 0);
    }


    /* =========================================================
       FEATURED
       ========================================================= */

    .coma-menu-featured {
      display: flex;

      flex-direction: column;

      justify-content: center;

      max-width: 500px;
    }


    .coma-featured-label {
      margin-bottom: 15px;

      color: #777777;

      font-size: 10px;
      font-weight: 700;

      letter-spacing: 4px;
    }


    .coma-featured-card {
      position: relative;

      overflow: hidden;

      background: #151515;
    }


    .coma-featured-image {
      position: relative;

      aspect-ratio: 4 / 5;

      overflow: hidden;
    }


    .coma-featured-image::after {
      content: "";

      position: absolute;

      inset: 0;

      background:
        linear-gradient(
          to top,
          rgba(0,0,0,0.85),
          transparent 60%
        );
    }


    .coma-featured-image img {
      width: 100%;
      height: 100%;

      object-fit: cover;

      display: block;

      transition:
        transform 0.8s ease;
    }


    .coma-featured-card:hover .coma-featured-image img {
      transform: scale(1.06);
    }


    .coma-featured-info {
      position: absolute;

      left: 30px;
      right: 30px;
      bottom: 25px;

      z-index: 2;
    }


    .coma-featured-category {
      display: block;

      margin-bottom: 10px;

      font-size: 10px;
      font-weight: 700;

      letter-spacing: 3px;
    }


    .coma-featured-info h2 {
      margin: 0;

      font-size: clamp(30px, 3vw, 55px);

      font-weight: 800;

      line-height: 0.9;

      letter-spacing: -2px;
    }


    .coma-featured-meta {
      display: flex;

      justify-content: space-between;

      margin-top: 25px;
      padding-top: 12px;

      border-top: 1px solid rgba(255,255,255,0.3);

      font-size: 9px;
      font-weight: 700;

      letter-spacing: 2px;
    }


    /* =========================================================
       FOOTER
       ========================================================= */

    .coma-menu-footer {
      position: absolute;

      z-index: 3;

      left: 8vw;
      right: 8vw;
      bottom: 30px;

      display: flex;

      justify-content: space-between;

      color: #555555;

      font-size: 9px;
      font-weight: 700;

      letter-spacing: 2px;
    }


    /* =========================================================
       MENU ANIMATION
       ========================================================= */

    .coma-menu-link {
      opacity: 0;

      transform: translateY(30px);
    }


    .coma-menu-overlay.active .coma-menu-link {
      animation: comaMenuLinkIn 0.6s ease forwards;
    }


    .coma-menu-overlay.active .coma-menu-link:nth-child(1) {
      animation-delay: 0.08s;
    }

    .coma-menu-overlay.active .coma-menu-link:nth-child(2) {
      animation-delay: 0.14s;
    }

    .coma-menu-overlay.active .coma-menu-link:nth-child(3) {
      animation-delay: 0.20s;
    }

    .coma-menu-overlay.active .coma-menu-link:nth-child(4) {
      animation-delay: 0.26s;
    }

    .coma-menu-overlay.active .coma-menu-link:nth-child(5) {
      animation-delay: 0.32s;
    }

    .coma-menu-overlay.active .coma-menu-link:nth-child(6) {
      animation-delay: 0.38s;
    }


    @keyframes comaMenuLinkIn {

      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }

    }


    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 900px) {

      .coma-header {
        height: 70px;
        padding: 0 20px;
      }


      .coma-logo {
        font-size: 21px;
      }


      .coma-menu-inner {
        display: block;

        padding: 100px 25px 80px;

        overflow-y: auto;
      }


      .coma-menu-featured {
        display: none;
      }


      .coma-menu-title {
        font-size: clamp(35px, 12vw, 60px);

        letter-spacing: -2px;
      }


      .coma-menu-link {
        grid-template-columns: 45px 1fr 30px;
      }


      .coma-menu-close {
        top: 20px;
        right: 20px;
      }


      .coma-menu-footer {
        left: 25px;
        right: 25px;
        bottom: 20px;
      }

    }


    @media (max-width: 500px) {

      .coma-menu-label {
        display: none;
      }


      .coma-menu-eyebrow {
        margin-bottom: 20px;
      }


      .coma-menu-link {
        padding: 13px 0;
      }


      .coma-menu-title {
        font-size: 36px;
      }


      .coma-menu-number {
        font-size: 9px;
      }

    }
</style>
  <header class="coma-header">

    <a href="/" class="coma-logo">
      COMA<span>NEWS</span>
    </a>

    <button type="button" class="coma-menu-toggle" id="comaMenuToggle">
      <span class="coma-menu-label">MENU</span>

      <span class="coma-menu-icon">
            <i></i>
            <i></i>
            <i></i>
        </span>
    </button>

  </header>


  <div class="coma-menu-overlay" id="comaMenuOverlay">

    <div class="coma-menu-background"></div>

    <button
      type="button"
      class="coma-menu-close"
      id="comaMenuClose">

      <span>CLOSE</span>
      <strong>×</strong>

    </button>


    <div class="coma-menu-inner">

      <div class="coma-menu-navigation">

        <div class="coma-menu-eyebrow">
          COMA NEWS
        </div>


        <nav>

          <a href="/" class="coma-menu-link">
            <span class="coma-menu-number">01</span>
            <span class="coma-menu-title">HOME</span>
            <span class="coma-menu-arrow">↗</span>
          </a>


          <a href="/games" class="coma-menu-link">
            <span class="coma-menu-number">02</span>
            <span class="coma-menu-title">GAMES</span>
            <span class="coma-menu-arrow">↗</span>
          </a>


          <a href="/news" class="coma-menu-link">
            <span class="coma-menu-number">03</span>
            <span class="coma-menu-title">NEWS</span>
            <span class="coma-menu-arrow">↗</span>
          </a>


          <a href="/reviews" class="coma-menu-link">
            <span class="coma-menu-number">04</span>
            <span class="coma-menu-title">REVIEWS</span>
            <span class="coma-menu-arrow">↗</span>
          </a>


          <a href="/previews" class="coma-menu-link">
            <span class="coma-menu-number">05</span>
            <span class="coma-menu-title">PREVIEWS</span>
            <span class="coma-menu-arrow">↗</span>
          </a>


          <a href="/features" class="coma-menu-link">
            <span class="coma-menu-number">06</span>
            <span class="coma-menu-title">FEATURES</span>
            <span class="coma-menu-arrow">↗</span>
          </a>

        </nav>

      </div>


      <div class="coma-menu-featured">

        <div class="coma-featured-label">
          FEATURED
        </div>


        <div class="coma-featured-card">

          <div class="coma-featured-image">
            <img
              src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1000&q=80"
              alt="Featured game"
            >
          </div>


          <div class="coma-featured-info">

                    <span class="coma-featured-category">
                        GAME
                    </span>

            <h2>
              THE NEXT<br>
              GENERATION
            </h2>

            <div class="coma-featured-meta">
              <span>COMA NEWS</span>
              <span>2026</span>
            </div>

          </div>

        </div>

      </div>

    </div>


    <div class="coma-menu-footer">

      <span>© COMA NEWS</span>

      <span>GAMES · CULTURE · MEDIA</span>

    </div>

  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {

      const menuToggle = document.getElementById("comaMenuToggle");
      const menuClose = document.getElementById("comaMenuClose");
      const menuOverlay = document.getElementById("comaMenuOverlay");


      function openMenu() {

        menuOverlay.classList.add("active");

        document.body.style.overflow = "hidden";

      }


      function closeMenu() {

        menuOverlay.classList.remove("active");

        document.body.style.overflow = "";

      }


      menuToggle.addEventListener("click", function () {

        openMenu();

      });


      menuClose.addEventListener("click", function () {

        closeMenu();

      });


      document.addEventListener("keydown", function (event) {

        if (event.key === "Escape") {

          closeMenu();

        }

      });


      menuOverlay.addEventListener("click", function (event) {

        if (event.target === menuOverlay) {

          closeMenu();

        }

      });

    });
  </script>
