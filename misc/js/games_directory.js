let Description = "";
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
    return data.description || "No description available"; // Return the description

  } catch (error) {
    console.error("Error fetching description:", error);
    return "Failed to load description";
  }
}

async function fetchTrailer(ID) {
  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf"
  });

  const response = await fetch(`https://api.rawg.io/api/games/${ID}/movies?${params}`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json'
    }
  });

  const movies = await response.json();

  // Extract the 'max' trailer links
  const trailerLinks = movies.results.map(result => result.data.max);

  return trailerLinks;
}

async function fetchGames(page=1) {
  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf",
    page: page,
    count: 10000
  });

  try {
    const response = await fetch(`https://api.rawg.io/api/games?${params}`, {
      method: 'GET',
      mode: 'cors',
      headers: {
        'Accept': 'application/json',
      }
    });

    const data = await response.json();
    let output = `<div class="game-container"><h1>Games</h1>`;

    // Process each game sequentially (for...of allows async/await)
    for (const item of data.results) {
      let ID = item.id;

      // Call fetchDescription and wait for the description
      let gameDescription = await fetchDescription(ID);
      let gameDescriptionSlice = (await fetchDescription(ID)).slice(0, 500);
      let gameTrailer = await fetchTrailer(ID);
      output += `<h3><a href="">${item.name}</a></h3>`;
      output += `<p><strong>Release Date:</strong> ${item.released}</p>`;
      output += `<img src="${item.background_image}" width=200 height=200/>`;
      output += `<p>${gameDescriptionSlice}</p>`;
      output += `<p><strong>Rating:</strong> ${item.rating}</p>`;
      output += `<p><strong>Metacritic:</strong>${item.metacritic}</p>`;

      // Platforms
      let platformNames = item.platforms.map(p => p.platform.name).join(", ");
      output += `<p><strong>Platforms:</strong> ${platformNames}</p>`;

      // System Requirements
      item.platforms.forEach(p => {
        if (p.requirements_en) {
          let platformRequirementsMin = p.requirements_en.minimum ? `${p.requirements_en.minimum}` : '';
          let platformRequirementsMax = p.requirements_en.recommended ? `${p.requirements_en.recommended}` : '';

          if (platformRequirementsMin || platformRequirementsMax) {
            output += `<p>${platformRequirementsMin}</p>`;
            output += `<p>${platformRequirementsMax}</p>`;
          }
        }
      });

      // Genres
      output += `<strong>Genre(s):</strong>`;
      item.genres.forEach(g => {
        output += `<p>${g.name}</p>`;
      });

      // Store Availability
      output += `<strong><p>Available in store:</strong></p>`;
      item.stores.forEach(s => {
        output += `<a href="https://${s.store.domain}" target="_blank">${s.store.name}</a>`;
      });

      // ESRB Rating
      output += `<strong><p>ESRB RATING</p></strong>`;
      output += `<p>${item.esrb_rating ? item.esrb_rating.name : "Not Rated"}</p>`;

      output += `<strong>Modes</strong>`;
      item.tags.forEach(tag => {
        output += `<p>${tag.name}</p>`;
      });

      // Screenshots
      output += `<strong><p>Screenshots</p></strong>`;
      item.short_screenshots.forEach(s => {
        output += `<a href="${s.image}"><img src="${s.image}" width=200 height=200/></a>`;
      });

      //Trailers
      output += `<strong><p>Trailers</p></strong>`;
      for (let i = 0; i < gameTrailer.length; i++) {
        output += `<video width="320" height="320" controls>`;
        output += `<source src="${gameTrailer[i]}" type="video/mp4">`;
        output += `</video>`;
      }
      output += `</div>`;
    }

    // Update the HTML content after processing all games
    document.getElementById("games").innerHTML = output;

  } catch (error) {
    console.error("Error fetching games:", error);
    document.getElementById("games").innerHTML = "<p>Failed to load games.</p>";
  }
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
