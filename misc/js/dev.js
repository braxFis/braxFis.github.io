let currentPage = 1;

//Fetch Developers from the API
function fetchDevelopers(page=1){
  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf",
    count: 1000,
    page: page
  });

  fetch(`https://api.rawg.io/api/developers?${params}`, {
    method: 'GET',
    mode: 'cors',
    headers: {
      'Accept': 'application/json',
    }
  })
    .then(response => response.json())
    .then(data => {
      let output = "<h1>Developers</h1>";
      data.results.forEach((item) => {
        output += `<h3>${item.name}</h3>`;
        output += `<img src="${item.image_background}" width=200 height=200/>`;
        output += `<p><strong>Total games:</strong>${item.games_count}</p>`;
        output += `<p><strong>Best known for:</strong>${item.games.map(game => game.name).slice(0, 3)}`;
      });

      document.getElementById("developers").innerHTML = output;
    });
}

// Then on button click:
document.addEventListener("DOMContentLoaded", () => {
  const nextBtn = document.getElementById("nextBtn");
  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      currentPage++;
      fetchDevelopers(currentPage);
    });
  } else {
    console.warn("Next page button not found.");
  }

  // Run fetchGames once on load
  fetchDevelopers();
});
