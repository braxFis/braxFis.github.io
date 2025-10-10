<!-- Add to your <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.to-top {
position: fixed;
bottom: 30px;
right: 30px;
background: #333;
color: white;
padding: 12px 15px;
border-radius: 50%;
text-align: center;
font-size: 20px;
cursor: pointer;
z-index: 1002;
display: none; /* hidden by default */
transition: opacity 0.3s ease, visibility 0.3s;
}

.to-top:hover {
background: #555;
}
</style>
<a href="#" class="to-top" id="toTop">
    <i class="fas fa-chevron-up"></i>
</a>

<footer class="main-footer">
    <p>&copy; <?= date('Y') ?> - OWL Project - All Rights Reserved</p>
    <?php foreach ($footers as $footer):?>
        <li><?= htmlspecialchars($footer->label) ?></li>
    <?php endforeach;?>

    <?php include __DIR__ . '/../newsletter/newsletter.php' ?>

</footer>

<script>
    const toTop = document.getElementById('toTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            toTop.style.display = 'block';
        } else {
            toTop.style.display = 'none';
        }
    });

    toTop.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    //DnD starts from here

</script>
</body>