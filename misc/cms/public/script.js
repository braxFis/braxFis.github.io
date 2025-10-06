const zones = ["sidebar","main","footer"];
const state = {};

// Init state from DOM
zones.forEach(zone => {
  state[zone] = Array.from(document.getElementById(zone).children).map(el => el.dataset.widget);

  new Sortable(document.getElementById(zone), {
    group: "widgets",
    animation: 150,
    onEnd: (evt) => {
      // Uppdatera state
      const from = evt.from.id;
      const to = evt.to.id;
      const item = state[from].splice(evt.oldIndex,1)[0];
      state[to].splice(evt.newIndex,0,item);
      console.log("Updated state:", state);
    }
  });
});

// Save-knapp
document.getElementById("save").addEventListener("click", () => {
  fetch("/dnd", {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify(state)
  }).then(r=>r.json()).then(data => alert("Saved!"));
});

console.log("Initial state", state);
