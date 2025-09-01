//Use the client id and make it immutable
const CLIENT_ID = "AIHg7YkE1D3qDZAdT5Gj8PZ70SEa";

//Use the client secret and make it immutable
const CLIENT_SECRET = "chPfiZKgepM7WH1lklGePGO71zYa";

//Create an empty access token variable..
let ACCESS_TOKEN = "";

//Create a variable to store and retreive the value of the input field (origin)
let origin = "";

//Create a variable to store and retreive the value of the input field (destination)
let destination = "";

//Create a variable to store and retreive the value of the input field (time)
let time = "";

//Create a variable to store and retreive the value of the input field (time2)
let time2 = "";

//Store the origin GID
let originGID = "";

//Store the destination GID
let destinationGID = "";

//Store the time value
let timeID = "";

//Get an access token automatically..
function getAccessToken(){
    fetch("https://ext-api.vasttrafik.se/token", {
        method: "POST",
        headers: {
            "Authorization": "Basic " + btoa(CLIENT_ID + ":" + CLIENT_SECRET),
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "grant_type=client_credentials"
    })
        .then(response => response.json())
        .then(data => {
            ACCESS_TOKEN = data.access_token;
            console.log(ACCESS_TOKEN);
        })
        .catch(error => console.error("Error:", error));
}

//Load the getAccessToken function..
getAccessToken();

//Make sure the acess token is retrievable from outside functions..
function tryAccessToken(){
    console.log(ACCESS_TOKEN);
}

/*async function getTimeValue(){
    time = document.getElementById("time").value;
    time2 = document.getElementById("time2").value;
    localStorage.setItem("time", time);
    localStorage.setItem("time2", time2);
}*/

async function getOriginGID() {
    origin = document.getElementById("origin").value;
    localStorage.setItem("origin", origin);
    try {
        const response = await fetch(`https://ext-api.vasttrafik.se/pr/v4/locations/by-text?q=${encodeURIComponent(origin)}`, {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Authorization": `Bearer ${ACCESS_TOKEN}`
            }
        });

        const data = await response.json();

        if (data.results && data.results.length > 0) {
            originGID = data.results[0].gid;
        } else {
            throw new Error("No results found for origin.");
        }
    } catch (error) {
        console.error("Error fetching Origin GID:", error);
    }
}

async function getDestinationGID() {
    destination = document.getElementById("destination").value;
    localStorage.setItem("destination", destination);
    try {
        const response = await fetch(`https://ext-api.vasttrafik.se/pr/v4/locations/by-text?q=${encodeURIComponent(destination)}&types=stoparea`, {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Authorization": `Bearer ${ACCESS_TOKEN}`
            }
        });

        const data = await response.json();

        if (data.results && data.results.length > 0) {
            destinationGID = data.results[0].gid;
        } else {
            throw new Error("No results found for origin.");
        }
    } catch (error) {
        console.error("Error fetching Origin GID:", error);
    }
}

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

async function searchJourney() {
    await getOriginGID();  // Wait for Origin GID
    await getDestinationGID();  // Wait for Destination GID
    /*await getTimeValue();*/
    if (!originGID || !destinationGID) {
        console.error("Missing Origin or Destination GID!");
        return;
    }

    const params = new URLSearchParams({
        originGid: originGID,
        destinationGID: destinationGID,
        /*dateTime: time + "T" + time2 + ":00Z"*/
    });

    fetch(`https://ext-api.vasttrafik.se/pr/v4/journeys?${params}`, {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "Authorization": `Bearer ${ACCESS_TOKEN}`
        }
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById("load").innerHTML = `
                          <div class="time-table">
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Departed</th>
                                <th>Departure Name</th>
                                <th>Departure Time</th>
                                <th>Arrival Time</th>
                                <th>Arrival Name</th>
                                <th>Trip Time (Minutes)</th>
                                <th>Transport Mode</th>
                                <th>Transport Number</th>
                                <th>Direction</th>
                                <th>Wheelchair</th>
                                <th>Platform</th>
                                <!--<th>Map View</th>-->
                                <!--<th>Latitude</th>-->
                                <!--<th>Longitude</th>-->
                                <th>Zon</th>
                            </tr>
                        </thead>
                        <tbody>
                    
            ` + [...new Set(data.results.map(result =>
                `
                            ${result.tripLegs.map(leg => `
                            <tr>
                                <td>${result.isDeparted ? "Yes" : "No"}</td>
                                <td>${leg.origin.stopPoint.name}</td>
                                <td>${leg.plannedDepartureTime.split("T")[1].split("+")[0].split(".")[0]}</td>
                                <td>${leg.plannedArrivalTime.split("T")[1].split("+")[0].split(".")[0]}</td>
                                <td>${leg.destination.stopPoint.name}</td>
                                <td>${leg.plannedDurationInMinutes}</td>
                                <td>${leg.serviceJourney.line.transportMode}</td>
                                <td><div style="border-radius: 10px;margin-left: 50px; text-align: center; width: 25px; height: 25px; border: 1px solid ${leg.serviceJourney.line.borderColor}; background-color: ${leg.serviceJourney.line.backgroundColor}">${leg.serviceJourney.line.shortName}</div></td>
                                <td>${leg.serviceJourney.direction}</td>
                                <td><img src="/img/MUTCD_D9-6.svg.png" width="25" height="25"/> ${leg.serviceJourney.line.isWheelchairAccessible ? "Yes" : "No"}</td>
                                <td>${leg.origin.stopPoint.platform || "N/A"}</td>
                                <!--<td><a href="/map.html"/>Map View</a></td>-->
                            </tr>
                            `)}
                `
            ))] + `</tbody></table></div>`;

            setTimeout(addSorting, 500);
            addSorting();

            document.getElementById("origins").innerHTML = data.results.map(result =>
            `
            <option value="${result.tripLegs.map(leg => leg.origin.stopPoint.name)}">${result.tripLegs.map(leg => leg.origin.stopPoint.name)}</option>
            `
            );

            document.getElementById("destinations").innerHTML = data.results.map(result =>
            `
            <option value="${result.tripLegs.map(leg => leg.destination.stopPoint.name)}">${result.tripLegs.map(leg => leg.destination.stopPoint.name)}</option>
            `
            );

        })
        .catch(error => console.error("Error fetching journey:", error));
}

async function searchProducts(){
    await getOriginGID();  // Wait for Origin GID
    await getDestinationGID();  // Wait for Destination GID

    if (!originGID || !destinationGID) {
        console.error("Missing Origin or Destination GID!");
        return;
    }

    fetch(`https://ext-api.vasttrafik.se/pr/v4/products/journeyticket?originGid=${originGID}&destinationGid=${destinationGID}`, {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "Authorization": `Bearer ${ACCESS_TOKEN}`
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log("Journey Data:", data);
            document.getElementById("load").innerHTML += data.map(result =>
            `
            <div class="cost">
            <table border="1">
                <thead>
                    <tr>
                        <th>Zon</th>
                        <th>Valid for</th>
                        <th>Price</th>
                        <th>Age Restriction</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${result.ticketName}</td>
                        <td>${result.configurations.map(con => con.validityLength) + "minutes"}</td>
                        <td>${result.configurations.map(con => con.itemPrice) + "SEK"}</td>
                        <td>${result.configurations.map(con => con.ageType)}</td>
                    </tr>
                </tbody>           
            </table>
           </div>
            `
            );
        })
        .catch(error => console.error("Error fetching journey:", error));
}

function footer(){
    document.addEventListener("DOMContentLoaded", () => {
        fetch("http://localhost:63342/test/data/")
    })
}
function loadData(){
    const originSave = localStorage.getItem("origin");
    document.getElementById("origin").value = originSave;
    const destinationSave = localStorage.getItem("destination");
    document.getElementById("destination").value = destinationSave;
}

window.onload = loadData;

async function showStops() {
    const params = new URLSearchParams({
        /*number: number1,
        isRegular: true,
        isFlexibleBusService: false,
        isFlexibleTaxiService: false,
        isSpecialVehicleTransportConnectionPoint: false,
        includeTariffZones: true,
        includeTransportAuthority: false,
        includeGeometry: false,
        validAtDate: false,
        offset: offset,
        limit: limit,
        srid: srid*/

    });

    fetch(`https://ext-api.vasttrafik.se/geo/v2/StopPoints?number=204301&isRegularTraffic=true&isFlexibleBusService=false&isFlexibleTaxiService=false&isSpecialVehicleTransportConnectionPoint=false&includeTariffZones=true&includeTransportAuthority=false&includeMunicipality=true&includeGeometry=true&validAtDate=2023-09-07&offset=0&limit=10&srid=3006`, {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "Authorization": `Bearer ${ACCESS_TOKEN}`
        }
    })
        .then(response => response.json())
        .then(data => {
            data.stopPoints[0];
            setTimeout(addSorting, 500);
            addSorting();
        })
        .catch(error => console.error("Error fetching journey:", error));
}

function loadStops(){}

async function showDisturbances() {
    const params = new URLSearchParams({});

    fetch("https://ext-api.vasttrafik.se/ts/v1/traffic-situations", {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "Authorization": `Bearer ${ACCESS_TOKEN}`
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            data.map(result => {
                document.getElementById("load").innerHTML += `
                <div class="stop-point">
                    <h1>${result.title}</h1>
                    <h3>${result.description}</h3>
                    <p>${result.creationTime}</p>
                <div class="stops">
                    <p>Affected Stops: 
                        <ul>
                            ${result.affectedStopPoints.map(point => 
                                `
                                    <li>${point.name}</li>
                                    <li>${point.municipalityName}</li>
                                `)}
                        </ul>
                    </p>
                </div>
                
                <div class="lines">
                    <p>Affected Lines:
                            <ul>
                                ${result.affectedLines.map(line =>
                                    `
                                    <li>${line.directions.map(dir => dir.name)}</li>
                                    `
                                )}     
                            </ul>
                    </p>
                </div>
                </div>
                `
            });
            setTimeout(addSorting, 500);
            addSorting();
        })
        .catch(error => console.error("Error fetching journey:", error));
}