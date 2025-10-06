// List of image URLs
const images = [
    "https://via.placeholder.com/600x300?text=Image+1",
    "https://via.placeholder.com/600x300?text=Image+2",
    "https://via.placeholder.com/600x300?text=Image+3",
    "https://via.placeholder.com/600x300?text=Image+4",
];

let currentIndex = 0;

// Next image function
function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    document.getElementById("galleryImage").src = images[currentIndex];
}

// Previous image function
function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    document.getElementById("galleryImage").src = images[currentIndex];
}