function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("componentId", ev.target.dataset.id);
}

function drop(ev) {
  ev.preventDefault();
  const id = ev.dataTransfer.getData("componentId");
  const targetController = ev.target.closest(".controller-zone").dataset.controller;

  fetch("/component/move", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `id=${id}&target=${targetController}`
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        location.reload(); // uppdatera vyerna
      }
    });
}
