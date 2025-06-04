function volvoRSS() {
  const url = "https://api.rss2json.com/v1/api.json?rss_url=https://www.media.volvocars.com/global/en-gb/rss/pressreleases/feed.rss";

  fetch(url)
    .then(response => response.json())
    .then(data => {
      console.log(data); // Check JSON structure
      let output = "";

      data.items.forEach(item => {
        output += `<img src="/img/volvo_spread_word_mark.svg" width=200 height=200/>`;
        output += `<h3><a href="${item.link}" target="_blank">${item.title}</a></h3>`;
        output += `<p>${item.description}</p>`;
      });

      document.getElementById("rss-feed").innerHTML = output;
    })
    .catch(error => console.error("Error fetching RSS:", error));
}

volvoRSS();

function aftonbladetRSS() {
  const url = "https://rss.aftonbladet.se/rss2/small/pages/sections/senastenytt/";

  fetch(url)
    .then(response => response.text()) // Get response as text (XML)
    .then(str => new window.DOMParser().parseFromString(str, "text/xml")) // Parse XML
    .then(data => {
      console.log(data); // Check XML structure in console

      const items = data.querySelectorAll("item"); // Select all <item> elements
      let output = "";

      items.forEach(item => {
        const title = item.querySelector("title").textContent;
        const link = item.querySelector("link").textContent;
        const description = item.querySelector("description").textContent;

        output += `<div class="news-container">`;
        output += `<img src="/img/share-aftonbladet.jpg" width=200 height=200/>`;
        output += `<h3><a href="${link}" target="_blank">${title}</a></h3>`;
        output += `<p>${description}</p>`;
        output += `</div>`;
      });

      document.getElementById("rss-feed-aftonbladet").innerHTML = output;
    })
    .catch(error => console.error("Error fetching RSS:", error));
}

aftonbladetRSS();
