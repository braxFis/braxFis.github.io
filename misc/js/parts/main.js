let currentPage = 1;

async function fetchDescription(ID) {
  if (!ID) return "No description available"; // Handle missing ID

  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf",
  });

  try {
    const response = await fetch(`https://api.rawg.io/api/games/${ID}?${params}`, {
      method: 'GET',
      mode: 'cors',
      headers: {
        'Accept': 'application/json'
      }
    });

    const data = await response.json();
    return (data.description).slice(0, 500) || "No description available"; // Return the description

  } catch (error) {
    console.error("Error fetching description:", error);
    return "Failed to load description";
  }
}

async function fetchGames(page=1) {
  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf",
    page: page,
    count: 10000
  });

  fetch(`https://api.rawg.io/api/games?${params}`, {
    method: 'GET',
    mode: 'cors',
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(res => res.json())
  .then(async data => {
    let output = "";
    for (const game of data.results) {
      let ID = game.id;
      let gameDescription = await fetchDescription(ID);
      output +=
        `
        <div class="game">
            <img src="${game.background_image}" width="250" height="250"/>
            <div class="description">
                <h4>${game.name}</h4>
                <p>${gameDescription}</p>
            </div>
        </div>
      `
    }

    document.getElementById("games").innerHTML = output;
  })
}

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
