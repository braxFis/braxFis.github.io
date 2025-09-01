let crimesData = [];
let visibleCrimes = 10;
let locationsData = [];
let visibleLocations = 10;
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
function getCrimes() {
    fetch(`https://polisen.se/api/events`, {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            crimesData = data; // Store all data
            displayCrimes();   // Show first 10
        });
}
function displayCrimes() {
    const crimesToShow = crimesData.slice(0, visibleCrimes);

    document.getElementById('load').innerHTML = `
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>URL</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Map View</th>
                </tr>
            </thead>
            <tbody>
                ${crimesToShow.map(data => `
                    <tr>
                        <td>${data.name}</td>
                        <td>${data.datetime}</td>
                        <td>${data.summary}</td>
                        <td><a href="${data.url}" target="_blank">Link</a></td>
                        <td>${data.type}</td>
                        <td>${data.location?.name || "N/A"}</td>
                        <td>${data.location?.gps || "N/A"}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
        <button onclick="loadMore()" id="loadMoreBtn">Load More</button>
    `;
    setTimeout(addSorting, 500);
    addSorting();
}
function displayLocations() {
    const locationsToShow = locationsData.slice(0, visibleLocations);

    document.getElementById('load2').innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Station Name</th>
                        <th>Location</th>
                        <th>Website</th>
                        <th>Services</th>
                    </tr>
                </thead>
                <tbody>
                    ${locationsToShow.map(station => `
                        <tr>
                            <td>${station.name}</td>
                            <td>${station.location?.name || "N/A"}</td>
                            <td><a href="${station.url}" target="_blank">Visit</a></td>
                            <td>${station.services.map(service => service.name).join(", ")}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            <button onclick="loadMore()" id="loadMoreBtn2">Load More</button>

        `;

}
function loadMore() {
    visibleCrimes += 10;
    visibleLocations += 10;
    displayCrimes();
    displayLocations();

    if (visibleCrimes >= crimesData.length) {
        document.getElementById("loadMoreBtn").style.display = "none";
    }

    if(visibleLocations >= locationsData.length) {
        document.getElementById("loadMoreBtn2").style.display = "none";
    }
}
function getLocations() {
    fetch(`https://polisen.se/api/policestations`, {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            locationsData = data;
            displayLocations();
            setTimeout(addSorting, 500);
            addSorting();
        });
}
getCrimes();
getLocations();