<?php include __DIR__ . '/partials/header.php'; ?>

<?php include __DIR__ . '/partials/menu.php'; ?>


  <!-- =====================================================
       GALLERY
       ===================================================== -->

<?php if (!empty($galleryItems)): ?>

  <div class="gallery">

    <?= \app\widgets\GalleryWidget::render($galleryItems) ?>

  </div>

<?php endif; ?>


  <!-- =====================================================
       PAGE CONTENT
       ===================================================== -->

  <div style="margin-top:100px"></div>

  <main class="page-content">

    <?= $content ?>

  </main>


  <!-- =====================================================
       SCREENSHOTS
       ===================================================== -->

<?php if (!empty($games)): ?>

  <div class="screenshot-container">
    <?= \app\widgets\PictureWidget::renderGallery($games) ?>
  </div>

  <?php if (!empty($trailerGames)): ?>

    <div class="latest-videos">
      <?= \app\widgets\TrailerWidget::renderTrailers($trailerGames) ?>
    </div>

  <?php endif; ?>

<?php endif; ?>


<?php include __DIR__ . '/partials/footer.php'; ?>
