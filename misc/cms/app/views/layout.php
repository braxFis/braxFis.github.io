<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/menu.php'; ?>

<main class="page-content">
    <?= $content ?>
</main>

<div class="screenshot-container">
<?php
use app\widgets\PictureWidget;
echo (new PictureWidget)::renderImageSideBar("3498");
?>
</div>

<div class="latest-videos">
<?php
use app\widgets\TrailerWidget;
echo (new TrailerWidget)::renderTrailerSideBar("3498");
?>
</div>

<script>
document.addEventListener('click', async (e) => {
    if (!e.target.classList.contains('load-more')) return;

    const btn = e.target;
    const container = document.getElementById('screenshot-container');
    const nextPage = parseInt(btn.dataset.page) + 1;
    btn.disabled = true;
    btn.textContent = "Laddar...";

    try {
        const res = await fetch(`/ajax/screenshots.php?id=3498&page=${nextPage}`);
        const data = await res.json();
        if (data.html) {
            // Lägg till ny HTML före knappen
            btn.insertAdjacentHTML('beforebegin', data.html);
            btn.remove(); // ta bort gamla knappen
        } else {
            btn.textContent = "Inga fler bilder";
        }
    } catch {
        btn.textContent = "Fel vid hämtning";
    }
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>