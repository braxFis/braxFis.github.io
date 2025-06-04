async function fetchDevPub(ID) {
  if (!ID) return "No description available"; // Handle missing ID

  const params = new URLSearchParams({
    key: "8bc47dab600645ac9164f534d0182baf",
  });

  fetch(`https://api.rawg.io/api/games/${ID}?${params}`, {
    method: 'GET',
    mode: 'cors',
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(response => response.json())
    .then(data => {
      data.developers.map(dev => { return dev.name; });
    })
}
