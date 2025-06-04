const API_KEY = "8bc47dab600645ac9164f534d0182baf";
const PAGE_SIZE = 40;
const MAX_PAGES = 10;
const BATCH_SIZE = 5;

async function fetchGamesPage(page) {
  const params = new URLSearchParams({
    key: API_KEY,
    page: page,
    page_size: PAGE_SIZE
  });

  const res = await fetch(`https://api.rawg.io/api/games?${params}`);
  const data = await res.json();
  return data.results;
}

async function fetchGameTrailers(gameId) {
  const params = new URLSearchParams({ key: API_KEY });
  const res = await fetch(`https://api.rawg.io/api/games/${gameId}/movies?${params}`);
  const data = await res.json();
  return data.results; // Now correctly referencing 'results'
}

function renderTrailer(gameName, trailer, gameGenres) {
  const container = document.getElementById("videos");
  const block = document.createElement("div");
  block.className = "video-container";
  block.innerHTML = `
    <h3>${gameName} – ${trailer.name}</h3>
    <div class="genres">Genres: ${gameGenres.join(', ')}</div>
    <video width="640" height="320" controls poster="${trailer.preview}">
      <source src="${trailer.data.max}" type="video/mp4">
    </video>
    <div class="trailer-thumbnails">
      ${renderThumbnailCarousel(trailer)}
    </div>
  `;
  container.appendChild(block);
}

// Function to render the thumbnail carousel under each trailer
function renderThumbnailCarousel(trailer) {
  const thumbnails = trailer.results || [];  // Correct reference to 'results' for the thumbnails
  return thumbnails.map(tn => `
    <img src="${tn.preview}" class="trailer-thumb" alt="Thumbnail" onclick="changeVideoSource('${tn.preview}')"/>
  `).join('');
}

// Change the video source when a thumbnail is clicked
function changeVideoSource(thumbnail) {
  const video = document.querySelector("video");
  video.src = thumbnail;  // Change the source to the new thumbnail video
  video.play();
}

async function fetchAllGamesWithTrailers() {
  for (let i = 1; i <= MAX_PAGES; i += BATCH_SIZE) {
    const batchPages = [];

    for (let j = 0; j < BATCH_SIZE; j++) {
      const pageNum = i + j;
      if (pageNum <= MAX_PAGES) {
        batchPages.push(fetchGamesPage(pageNum));
      }
    }

    const allGames = await Promise.all(batchPages); // Fetch pages in parallel

    for (const games of allGames) {
      if (!games || games.length === 0) continue; // 🔍 Skip if the page is empty

      for (const game of games) {
        const gameGenres = game.genres.map(genre => genre.name);  // Get genre names

        fetchGameTrailers(game.id).then(trailers => {
          if (trailers.length > 0) {
            trailers.forEach(trailer => {
              renderTrailer(game.name, trailer, gameGenres);
            });
          }
        }).catch(err => {
          console.error(`Error fetching trailers for game ${game.name}:`, err);
        });
      }
    }

    // Small delay between batches to be kind to the API
    // await new Promise(resolve => setTimeout(resolve, 500));
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("videos");
  container.innerHTML = `<h1>Game Trailers</h1>`;
  fetchAllGamesWithTrailers();
});
