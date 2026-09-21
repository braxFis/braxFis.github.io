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

.main-footer {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 40px;
}

.main-footer > div {
  min-width: 0;
}

@media (max-width: 900px) {
  .main-footer {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 500px) {
  .main-footer {
    grid-template-columns: 1fr;
  }
}

</style>
<a href="#" class="to-top" id="toTop">
    <i class="fas fa-chevron-up"></i>
</a>
<?php
use modules\genre\models\Genre;
use modules\platform\models\Platform;
use modules\developer\models\Developer;
use modules\publisher\models\Publisher;
$genreModel = new Genre();
$platformModel = new Platform();
$developerModel = new Developer();
$publisherModel = new Publisher();
$genres = $genreModel->genres;
$platforms = $platformModel->platforms;
$developers = $developerModel->developers;
$publishers = $publisherModel->publishers;
?>
<footer class="main-footer">
    <div class="genres">
      <h1>GENRES</h1>
      <ul>
        <?php foreach ($genres as $key => $val):?>
          <li><a href="/genre/<?= $key;?>"><?= strtoupper($key);?></a></li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class="platforms">
      <h1>PLATFORMS</h1>
      <ul>
        <?php foreach ($platforms as $key => $val):?>
        <li><a href="/platform/<?= $key;?>"><?= strtoupper($key);?></a></li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class="publishers">
      <ul>
        <h1>PUBLISHERS</h1>
        <?php foreach ($publishers as $key => $val):?>
          <li><a href="/publisher/<?= $key;?>"><?= strtoupper($key);?></a></li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class="developers">
      <h1>DEVELOPERS</h1>
      <ul>
        <?php foreach ($developers as $key => $val):?>
          <li><a href="/developer/<?= $key;?>"><?= strtoupper($key);?></a></li>
        <?php endforeach;?>
      </ul>
    </div>
    <p>&copy; <?= date('Y') ?> - OWL Project - All Rights Reserved</p>
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
