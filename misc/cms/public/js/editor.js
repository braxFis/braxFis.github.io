document.addEventListener("DOMContentLoaded", () => {
  const palette = document.getElementById("palette");
  const canvas = document.getElementById("canvas");
  const saveBtn = document.getElementById("saveLayoutBtn");

  Sortable.create(palette, { group: { name: "shared", pull: "clone", put: false }, sort: false });
  Sortable.create(canvas, {
    group: { name: "shared", pull: false, put: true },
    animation: 150,
    onAdd(evt) {
      const type = evt.item.dataset.type;
      evt.item.remove();
      addField(type);
    }
  });

  function addField(type) {
    const field = document.createElement("div");
    field.className = "field";
    field.dataset.type = type;

    switch (type) {
      case "h1": field.innerHTML = `<h1 contenteditable="true">Rubrik</h1>`; break;
      case "p": field.innerHTML = `<p contenteditable="true">Textinnehåll</p>`; break;
      case "img": field.innerHTML = `<img src="https://via.placeholder.com/150">`; break;
      case "audio": field.innerHTML = `<audio controls src="https://www.w3schools.com/html/horse.mp3"></audio>`; break;
      case "video": field.innerHTML = `<video controls width="250"><source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4"></video>`; break;
    }

    canvas.appendChild(field);
    document.querySelector(".placeholder")?.remove();
  }

  saveBtn.addEventListener("click", () => {
    console.log("canvas:", canvas);
    console.log("canvas.dataset.slug:", canvas?.dataset.slug);

    const slug = canvas.dataset.slug;
    const layout = Array.from(canvas.querySelectorAll(".field")).map(el => {
      const type = el.dataset.type;
      let content = "";
      if (["h1", "p"].includes(type)) content = el.querySelector(type)?.innerText || "";
      if (type === "img") content = el.querySelector("img")?.src || "";
      if (type === "audio") content = el.querySelector("audio")?.src || "";
      if (type === "video") content = el.querySelector("video source")?.src || "";
      return { type, content };
    });
    
    fetch("/dragdrop/save", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ slug, layout })
    })
      .then(res => res.json())
      .then(data => alert(data.message))
      .catch(err => alert("Fel vid sparning: " + err));
  });
});
