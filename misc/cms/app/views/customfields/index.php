<style>
.custom-field {
    min-height: 24px;
    padding: 4px;
    margin: 4px 0;
    border: 1px dashed #aaa;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<h2>Custom Fields</h2>
<?php $tags = ['input', 'select', 'textarea', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'img']; ?>
<select id="fieldSelector">
  <option value="">-- Välj fälttyp --</option>
  <?php foreach($tags as $tag): ?>
    <option value="<?= $tag ?>"><?= $tag ?></option>
  <?php endforeach; ?>
</select>
<button id="addFieldBtn">Lägg till</button>

<div id="fieldsContainer"></div>
<button id="saveBtn">Spara</div>
<script>
const container = document.getElementById('fieldsContainer');
const selector = document.getElementById('fieldSelector');
const LOCAL_KEY = 'customFields';

document.addEventListener('DOMContentLoaded', () => {
// Funktion för att skapa ett element
function createField(tag, value = '') {
    const el = document.createElement(tag);
    el.classList.add('custom-field');

    if(tag === 'input') {
        el.type = 'text' || 'file';
        el.value = value;
    } else if(tag === 'textarea') {
        el.rows = 3;
        el.textContent = value;
    } else if(tag === 'select') {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Välj...';
        el.appendChild(option);

        // Om det finns ett värde, lägg till det som selected
        if(value){
            const opt = document.createElement('option');
            opt.value = value;
            opt.textContent = value;
            opt.selected = true;
            el.appendChild(opt);
        }
    } else if(tag === 'img') {
        el.src = value || 'https://via.placeholder.com/150';
        el.alt = 'Custom Image';
    } else if(tag === 'p' || tag === 'h1' || tag === 'h2' || tag === 'h3' || tag === 'h4' || tag === 'h5' || tag === 'h6' || tag === 'span') {
        el.contentEditable = true;
        el.textContent = value || `Skriv här (${tag})`;
    }
    container.appendChild(el);
}

// Ladda fälten från localStorage
function loadFields() {
    const saved = JSON.parse(localStorage.getItem(LOCAL_KEY) || '[]');
    saved.forEach(f => createField(f.tag, f.value));
}

// Lägg till nytt fält från rullgardin
document.getElementById('addFieldBtn').onclick = () => {
    const tag = selector.value;
    if(!tag) return;
    createField(tag);
    selector.value = '';
};

// Spara alla fält till localStorage
document.getElementById('saveBtn').onclick = () => {
    const all = [];
    container.querySelectorAll('.custom-field').forEach(el => {
        let value = 'Skriv här';
        if(el.tagName.toLowerCase() === 'input') value = el.value;
        else if(el.tagName.toLowerCase() === 'textarea') value = el.value || el.textContent;
        else if(el.tagName.toLowerCase() === 'select') value = el.value;
        else if(el.tagName.toLowerCase() === 'p') value = el.value || el.textContent || 'Skriv här';
        else if(el.tagName.toLowerCase() === 'h1') value = el.value || el.textContent;
        else if(el.tagName.toLowerCase() === 'h2') value = el.value || el.textContent;
        else if(el.tagName.toLowerCase() === 'h3') value = el.value || el.textContent;
        else if(el.tagName.toLowerCase() === 'h4') value = el.value || el.textContent;
        else if(el.tagName.toLowerCase() === 'h5') value = el.value || el.textContent;
        else if(el.tagName.toLowerCase() === 'h6') value = el.value || el.textContent;
        else if(el.tagName.toLowerCase() === 'img') value = el.src;
        else if(el.tagName.toLocaleLowerCase() === 'input' && el.type === 'file') value = el.value;
        else {
          el.contentEditable = true;
          el.textContent = value || `Skriv här ({$tag})`;
        }
        all.push({ tag: el.tagName.toLowerCase(), value });
    });
    localStorage.setItem(LOCAL_KEY, JSON.stringify(all));
    alert('Fälten sparade!');
};

// Kör vid load
loadFields();
});

// --- Drag & drop ---
  Sortable.create(container, {
    animation: 150,
    handle: ".field-block",
  });
</script>
