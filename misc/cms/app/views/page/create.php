<h1>Create Page</h1>

<form id="pageForm" action="/page/store" method="POST">
  <label for="title">Title</label>
  <input type="text" name="title" id="title" required>

  <label for="slug">Slug</label>
  <input type="text" name="slug" id="slug">
  
  <input type="hidden" name="layout" id="layoutInput">

  <button type="submit">Create Page</button>
</form>

<h2>Bygg layout</h2>
<div class="editor">
  <div id="palette">
    <div class="palette-item" data-type="h1">Rubrik H1</div>
    <div class="palette-item" data-type="h2">Underrubrik H2</div>
    <div class="palette-item" data-type="h3">Underrubrik H3</div>
    <div class="palette-item" data-type="h4">Underrubrik H4</div>
    <div class="palette-item" data-type="h5">Underrubrik H5</div>
    <div class="palette-item" data-type="h6">Underrubrik H6</div>
    <div class="palette-item" data-type="p">Text P</div>
    <div class="palette-item" data-type="img">Bild</div>
    <div class="palette-item" data-type="textarea">Textområde</div>
    <div class="palette-item" data-type="i">Italic</div>
    <div class="palette-item" data-type="audio">Audio</div>
    <div class="palette-item" data-type="video">Video</div>
  </div>

  <div id="canvas" data-slug="">
    <p class="placeholder">Dra element hit</p>
  </div>
</div>

<button id="saveLayoutBtn">Spara layout</button>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const palette = document.getElementById("palette");
  const canvas = document.getElementById("canvas");

  Sortable.create(palette, { group: { name: "shared", pull: "clone", put: false }, sort: false, animation: 150 });
  Sortable.create(canvas, { group: { name: "shared", pull: false, put: true }, animation: 150, ghostClass: "sortable-ghost",
      onAdd(evt){ const type = evt.item.dataset.type; evt.item.remove(); addField(type); } });

  function addField(type){
    const field = document.createElement("div");
    field.className = "field"; field.dataset.type = type;
    switch(type){
      case "h1":
      case "h2":
      case "h3":
      case "h4":
      case "h5":
      case "h6":
      case "p":
        field.innerHTML = `<${type} contenteditable="true">${type} text</${type}>`; break;
      case "img": field.innerHTML = `<img src="https://via.placeholder.com/150">`; break;
      case "textarea": field.innerHTML = `<textarea placeholder="Skriv text..."></textarea>`; break;
      case "a": field.innerHTML = `<a href="#" contenteditable="true">Länktext</a>`; break;
      case "audio": field.innerHTML = `<audio controls src="https://www.w3schools.com/html/horse.mp3"></audio>`; break;
      case "video": field.innerHTML = `<video controls width="250"><source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">Din webbläsare stödjer inte videouppspelning.</video>`; break;
      case "i": field.innerHTML = `<i contenteditable="true">Italic text</i>`; break;
    }
    canvas.appendChild(field); document.querySelector(".placeholder")?.remove();
  }

document.getElementById("saveLayoutBtn").addEventListener("click", () => {
  const layout = Array.from(canvas.querySelectorAll(".field")).map(el => {
    const type = el.dataset.type;
    let content = '';
    if (['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'].includes(type)) {
      content = el.querySelector(type)?.innerText || '';
    } else if (type === 'img') {
      content = el.querySelector('img')?.src || '';
    } else if (type === 'textarea') {
      content = el.querySelector('textarea')?.value || '';
    } else if (type === 'a'){
      content = el.querySelector('a')?.innerText || '';
    } else if (type === 'i'){
      content = el.querySelector('i')?.innerText || '';
    } else if (type === 'audio') {
      content = el.querySelector('audio')?.src || '';
    } else if (type === 'video') {
      content = el.querySelector('video source')?.src || '';
    }
    return { type, content };
  });

  document.getElementById("layoutInput").value = JSON.stringify(layout);
});

});

</script>
<style>
  .editor { display: flex; gap: 20px; }
  #palette, #canvas { border: 1px solid #ccc; padding: 10px; width: 45%; min-height: 200px; }
  .palette-item { border: 1px solid #000; padding: 5px; margin-bottom: 5px; cursor: grab; background: #f0f0f0; }
  .field { border: 1px dashed #666; padding: 5px; margin-bottom: 5px; background: #fafafa; }
  .sortable-ghost { opacity: 0.4; }
  .placeholder { color: #999; font-style: italic; }
</style>