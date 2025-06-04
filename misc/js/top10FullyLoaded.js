async function side_tab(key = 'tba') {
  const API_KEY = "8bc47dab600645ac9164f534d0182baf";
  let page = 1;
  let hasNextPage = true;
  let allResults = [];

  while (hasNextPage) {
    const params = new URLSearchParams({
      key: API_KEY,
      page: page
    });

    const startTime = performance.now();

    try {
      const response = await fetch(`https://api.rawg.io/api/games?${params}`);
      const data = await response.json();
      const endTime = performance.now();

      allResults = allResults.concat(data.results);

      if (data.next) {
        page++;
      } else {
        hasNextPage = false;
      }

      console.log("Started at:" + startTime);
      console.log("Ended at:" + endTime);

    } catch (error) {
      console.error("Failed to fetch data:", error);
      hasNextPage = false;
    }

  }

  // Adjusted to target the container inside tabContent
  const container = document.querySelector(`#tabContent #${key}`);
  if (container) {
    container.innerHTML = ""; // Clear previous content

    switch (key) {
      case 'tba':
        const upcoming = allResults.filter(game => game.tba === true);
        upcoming.forEach(result => {
          const html = `
            <div class="game">
              <img src="${result.background_image}" width="200" />
              <h6>${result.name}</h6>
            </div>
          `;
          container.innerHTML += html;
        });
        break;

      case 'metacritic':
        const topRated = allResults
          .filter(game => game.metacritic !== null)
          .sort((a, b) => b.metacritic - a.metacritic)
          .slice(0, 10);

        topRated.forEach(result => {
          const html = `
            <div class="game">
              <img src="${result.background_image}" width="200" />
              <h6>${result.name} (Metacritic: ${result.metacritic})</h6>
            </div>
          `;
          container.innerHTML += html;
        });
        break;
    }
  } else {
    console.error(`Element with id '${key}' not found inside tabContent.`);
  }
}

function showTab(tabId, el) {
  // Hide all panels
  document.querySelectorAll(".tab-panel").forEach(panel => {
    panel.style.display = "none";
  });

  // Show selected panel
  document.getElementById(tabId).style.display = "block";

  // Set active tab styling
  document.querySelectorAll(".tab-item").forEach(tab => {
    tab.classList.remove("active");
  });
  el.classList.add("active");

  // Load data if not loaded yet
  if (!document.getElementById(tabId).innerHTML.trim()) {
    side_tab(1, tabId);
  }
}

// Auto-load first tab
window.onload = () => {
  const firstTab = document.querySelector('.tab-item.active');
  if (firstTab) showTab('tba', firstTab);
};
