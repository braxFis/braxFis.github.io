let query2 = "";

async function query(){
  query2 = document.getElementById("search-query").value;
  return query2;
}

async function search(){
  const query3 = await query();

    const params = new URLSearchParams({
      key: "8bc47dab600645ac9164f534d0182baf",
      search: query3,
      page_size: 10,
      count: 10000
    });

try {
  const response = await fetch(`https://api.rawg.io/api/games?${params}`, {
    method: "GET",
    mode: "cors",
    headers: {
      "Content-Type": "application/json"
    }
  })

  const data = await response.json();

  //Quick fix
  allGames = document.querySelector('.gallery-container');
  allGames.style.display = "none";
  mainContent = document.getElementById("main-content");
  mainContent.style.display = "none";

  // Update the HTML content after processing all games
  document.getElementById("search").innerHTML = output;

} catch (error){
  console.error("Error fetching movies:", error);
}

}
