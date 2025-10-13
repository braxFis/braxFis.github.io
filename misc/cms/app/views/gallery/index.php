<style>

    #lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        z-index: 999;
    }

    #lightbox img {
        max-width: 90%;
        max-height: 80%;
        margin-bottom: 20px;
    }

    #lightbox-caption {
        color: white;
        font-size: 1.2em;
        text-align: center;
        max-width: 90%;
    }

    #lightbox-close {
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 3em;
        color: white;
        cursor: pointer;
    }

#gallery {
width: 100%;
height: 500px;
margin: auto;
position: relative;
overflow: hidden;
background: #111;
color: #fff;
margin-top: 20px;
/*border-radius: 20px 20px;*/
}

.slide {
position: absolute;
top: 0;
left: 0;
width: 1000px;
height: 100%;
opacity: 0;
transition: opacity 1s;
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
}

.slide.active {
opacity: 1;
z-index: 1;
}

.image-container {
width: 1000px !important;
height: 350px;
display: flex;
justify-content: center;
align-items: center;
}

.image-container img {
max-height: 100%;
width: 1000px !important;
max-width: 1000px !important;
object-fit: contain;
border-radius: 10px 10px;
margin-left: 700px;
}

.caption {
text-align: center;
padding: 10px;
margin-left: 700px;
}

/* Buttons */
.nav-btn {
position: absolute;
top: 50%;
transform: translateY(-50%);
font-size: 2em;
background: rgba(0, 0, 0, 0.4);
color: white;
border: none;
padding: 10px 20px;
cursor: pointer;
z-index: 2;
user-select: none;
}

#prevBtn {
left: 10px;
background: rgba(0, 100, 200, 0.9);
}

#nextBtn {
right: 10px;
    background: rgba(0, 100, 200, 0.9);
}

.nav-btn:hover {
background: rgba(255, 255, 255, 0.2);
}

    #thumbnails {
        margin: 20px auto;
        max-width: 800px;
        overflow-x: auto;
        overflow-y: hidden;
        display: flex;
        flex-wrap: nowrap; /* ✅ prevent wrapping */
        gap: 10px;
        justify-content: flex-start;
        scrollbar-width: thin;
        padding-bottom: 5px;
    }

    #thumbnails::-webkit-scrollbar {
        height: 6px;
    }

    #thumbnails::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }


.thumb {
    width: 80px;
    height: 60px;
    object-fit: cover;
    opacity: 0.6;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.thumb.active,
.thumb:hover {
    opacity: 1;
    border-color: #ffcc00;
}
    @media (max-width: 850px) {
        #gallery, #thumbnails {
            width: 100%;
            max-width: 100%;
        }
    }

    section li{
        list-style-type: none;
    }
</style>
<div id="gallery">
    <?php foreach ($images as $i => $image): ?>
        <div class="slide<?= $i === 0 ? ' active' : '' ?>">
            <div class="image-container">
                <img src="<?= $image['media'] ?>" alt="<?= $image['title'] ?>">
            </div>
            <div class="caption">
                <h3><?= $image['title'] ?></h3>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Navigation buttons -->
    <button id="prevBtn" class="nav-btn">&#10094;</button>
    <button id="nextBtn" class="nav-btn">&#10095;</button>
</div>

<!-- Thumbnail bar -->
<div id="thumbnails">
    <?php foreach ($images as $i => $image): ?>
        <img src="<?= $image['media'] ?>"
             data-index="<?= $i ?>"
             class="thumb<?= $i === 0 ? ' active' : '' ?>">
    <?php endforeach; ?>
</div>

<!-- Lightbox -->
<div id="lightbox" style="display: none;">
    <span id="lightbox-close">&times;</span>
    <img id="lightbox-img" src="">
    <p id="lightbox-caption"></p>
</div>

<script>
    const slides = document.querySelectorAll('.slide');
    const thumbs = document.querySelectorAll('.thumb');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let current = 0;
    let interval;

    function showSlide(index) {
        slides[current].classList.remove('active');
        thumbs[current].classList.remove('active');

        current = (index + slides.length) % slides.length;

        slides[current].classList.add('active');
        thumbs[current].classList.add('active');
    }

    function nextSlide() {
        showSlide(current + 1);
    }

    function prevSlide() {
        showSlide(current - 1);
    }

    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    thumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const index = parseInt(thumb.getAttribute('data-index'));
            showSlide(index);
        });
    });

    function startAutoSlide() {
        interval = setInterval(nextSlide, 3000);
    }

    function stopAutoSlide() {
        clearInterval(interval);
    }

    startAutoSlide();
    document.getElementById('gallery').addEventListener('mouseenter', stopAutoSlide);
    document.getElementById('gallery').addEventListener('mouseleave', startAutoSlide);
</script>


<script>
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const closeBtn = document.getElementById('lightbox-close');

    // Både thumbnails och stora bilder
    const allImages = document.querySelectorAll('.slide img, .thumb');

    allImages.forEach(img => {
        img.addEventListener('click', () => {
            lightboxImg.src = img.src;
            lightboxCaption.innerText = img.alt || '';
            lightbox.style.display = 'flex';
        });
    });

    closeBtn.addEventListener('click', () => {
        lightbox.style.display = 'none';
    });

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
        }
    });
</script>