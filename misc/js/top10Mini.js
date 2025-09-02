function side_tab(page = 1, key = 'tba') {
  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf",
    page: page,
    dates: "2026-01-01",
    ordering: "-rating"
  });

  fetch(`https://api.rawg.io/api/games?${params}`)
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById(key);
      container.innerHTML = "";

      switch (key) {
        case 'tba':
          data.results.forEach(result => {
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
          const topRated = data.results
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
    })
    .catch(error => {
      console.error("Error fetching data:", error);
    });
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
