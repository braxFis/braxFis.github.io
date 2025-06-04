let images = [];

async function gallery_rotator() {
  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf"
  });

  try {
    const response = await fetch(`https://api.rawg.io/api/games?${params}`);
    const data = await response.json();
    
    // Store all background images in the array
    images = data.results
      .map(item => item.background_image)
      .filter(Boolean); // remove any null/undefined images

    // Initialize the first image
    if (images.length > 0) {
      document.getElementById("galleryImage").src = images[0];
    }

  } catch (error) {
    console.error("Error loading gallery:", error);
  }
}

let currentIndex = 0;

// Load images on page load
gallery_rotator();

// Next image
function nextImage() {
  if (images.length === 0) return;
  currentIndex = (currentIndex + 1) % images.length;
  document.getElementById("galleryImage").src = images[currentIndex];
}

// Previous image
function prevImage() {
  if (images.length === 0) return;
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  document.getElementById("galleryImage").src = images[currentIndex];
}
