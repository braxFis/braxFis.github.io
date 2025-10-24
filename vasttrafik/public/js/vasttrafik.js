document.getElementById("journeyForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const origin = document.getElementById("origin").value;
    const destination = document.getElementById("destination").value;

    const response = await fetch(`/index.php?route=journey/search&origin=${origin}&destination=${destination}`);
    const data = await response.json();

    document.getElementById("results").innerText = JSON.stringify(data, null, 2);
});
