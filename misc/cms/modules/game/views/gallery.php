<style>
.gallery-container {
    width: 80%;
    margin: 0 auto;
    text-align: center;
    position: relative;
}

.image-container {
    margin-bottom: 20px;
}

.gallery-image {
    width: 600px;
    max-width: 800px;
    height: auto;
    border-radius: 8px;
}

.controls {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.control-button {
    background-color: #4caf50;
    color: white;
    padding: 10px 20px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.control-button:hover {
    background-color: #45a049;
}
</style>
<section id="gallery">
  <?php if (!empty($images)): ?>
    <div class="gallery-container">
      <button onclick="prevImage()">◀</button>
      <img id="galleryImage" src="<?= htmlspecialchars($images[0]['background_image']) ?>" alt="">
      <button onclick="nextImage()">▶</button>
    </div>

    <script>
      const images = <?= json_encode(array_column($images, 'background_image')) ?>;
      let currentIndex = 0;

      function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        document.getElementById("galleryImage").src = images[currentIndex];
      }

      function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        document.getElementById("galleryImage").src = images[currentIndex];
      }
    </script>
  <?php else: ?>
    <p>Inga bilder hittades.</p>
  <?php endif; ?>
</section>
