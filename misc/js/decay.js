let currentPage = 1;

function fetchGames(page = 1) {
  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf",
    page: page
  });

  fetch(`https://api.rawg.io/api/games?${params}`)
    .then(response => response.json())
    .then(data => {
      // Render your game
      data.results.map((game) => {
        console.log(`Current Page: ${currentPage}`);
        console.log(`Current Game Name: ${game.name}`);
        console.log(game.metacritic);
      })
    });
}

// Then on button click:
document.addEventListener("DOMContentLoaded", () => {
  const nextBtn = document.getElementById("nextBtn");
  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      currentPage++;
      fetchGames(currentPage);
    });
  } else {
    console.warn("Next page button not found.");
  }

  // Run fetchGames once on load
  fetchGames();
});

