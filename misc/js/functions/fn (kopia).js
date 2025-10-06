function addSorting() {
    const table = document.querySelector(".time-table table");
    const headers = table.querySelectorAll("th");
    const tbody = table.querySelector("tbody");

    headers.forEach((header, columnIndex) => {
        header.addEventListener("mouseover", function (){
            header.style.cursor = "pointer";
        });

        header.addEventListener("click", function () {
            const rows = Array.from(tbody.querySelectorAll("tr"));
            const isAscending = header.dataset.order === "asc";
            header.dataset.order = isAscending ? "desc" : "asc";

            rows.sort((rowA, rowB) => {
                const cellA = rowA.children[columnIndex].textContent.trim();
                const cellB = rowB.children[columnIndex].textContent.trim();

                if (!isNaN(cellA) && !isNaN(cellB)) {
                    return isAscending ? cellA - cellB : cellB - cellA;
                } else {
                    return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
                }
            });

            tbody.innerHTML = "";
            tbody.append(...rows);
        });
    });
}

function loadTab(page, element) {
    const contentDiv = document.getElementById("tab-content");
    const loadingDiv = document.querySelector(".loading");

    // Show loading
    contentDiv.innerHTML = "";
    loadingDiv.style.display = "block";

    // Remove active class from all tabs, then add it to the clicked tab
    document.querySelectorAll(".tab").forEach(tab => tab.classList.remove("active"));
    element.classList.add("active");

    fetch(page)
        .then(response => {
            if (!response.ok) throw new Error("Page not found");
            return response.text();
        })
        .then(html => {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = html;

            // Extract inline scripts and execute them
            const scripts = tempDiv.querySelectorAll("script:not([src])");
            scripts.forEach(script => {
                const newScript = document.createElement("script");
                newScript.textContent = script.textContent;
                document.body.appendChild(newScript);
            });

            // Extract external scripts and load them
            const scriptSources = Array.from(tempDiv.querySelectorAll("script[src]")).map(s => s.src);
            loadScriptsSequentially(scriptSources);

            // Insert the HTML content (excluding scripts)
            contentDiv.innerHTML = tempDiv.innerHTML;

            // Apply fade-in effect
            contentDiv.classList.add("fade-in");
            setTimeout(() => contentDiv.classList.remove("fade-in"), 300);
        })
        .catch(error => {
            contentDiv.innerHTML = `<p>Error: ${error.message}</p>`;
        })
        .finally(() => {
            loadingDiv.style.display = "none";
        });
}

// Function to load external scripts in sequence (important for dependencies)
function loadScriptsSequentially(scripts) {
    if (scripts.length === 0) return;

    const script = document.createElement("script");
    script.src = scripts[0];
    script.onload = () => loadScriptsSequentially(scripts.slice(1)); // Load next script after this one
    document.body.appendChild(script);
}

function populateFooter(locationsToShow) {
    const namesList = document.getElementById("station-names");
    const locationsList = document.getElementById("station-locations");
    const websitesList = document.getElementById("station-websites");

    namesList.innerHTML = locationsToShow.map(station => `<li>${station.name}</li>`).join('');
    locationsList.innerHTML = locationsToShow.map(station => `<li>${station.location?.name || "N/A"}</li>`).join('');
    websitesList.innerHTML = locationsToShow.map(station => `
        <li>
            <a href="${station.url}" target="_blank">Visit</a> 
            - ${station.services.map(service => service.name).join(", ")}
        </li>
    `).join('');
}

// Load default tab on page load
window.onload = () => loadTab('/html/home.html', document.querySelector(".tab"));
