let fields = [];

function renderFields(saved = []) {
  fields = saved;
  const container = document.getElementById("fieldsContainer");
  container.innerHTML = "";

  fields.forEach((field, index) => {
    const div = document.createElement("div");
    div.className = "field-block";
    div.innerHTML = `
      <input type="text" value="${field.value || ""}" placeholder="Ange fältvärde" />
      <button onclick="removeField(${index})">❌</button>
    `;
    container.appendChild(div);
  });
}

document.getElementById("addFieldBtn").addEventListener("click", () => {
  fields.push({ value: "" });
  renderFields(fields);
});

function removeField(index) {
  fields.splice(index, 1);
  renderFields(fields);
}

document.getElementById("saveFieldsBtn").addEventListener("click", async () => {
  const inputs = document.querySelectorAll("#fieldsContainer input");
  fields = Array.from(inputs).map(input => ({ value: input.value }));
  const response = await fetch("/customfields/save", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ fields })
  });

  const result = await response.json();
  if (result.success) {
    alert("✅ Fält sparade!");
  } else {
    alert("❌ Kunde inte spara.");
  }
});
