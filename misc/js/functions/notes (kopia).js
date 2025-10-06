document.addEventListener("DOMContentLoaded", loadNotes);

function loadNotes() {
    const storedNotes = localStorage.getItem("notes");
    if (storedNotes) {
        const notes = JSON.parse(storedNotes);
        notes.forEach((note, index) => renderNote(note, index));
    }
}

function addNote() {
    const noteInput = document.getElementById("noteInput");
    const noteText = noteInput.value.trim();

    if (noteText) {
        const notes = JSON.parse(localStorage.getItem("notes") || "[]");
        notes.push(noteText);
        localStorage.setItem("notes", JSON.stringify(notes));
        renderNote(noteText, notes.length - 1);
        noteInput.value = "";
    }
}

function renderNote(note, index) {
    const notesList = document.getElementById("notesList");
    const li = document.createElement("li");
    li.innerHTML = `
                ${note} 
                <button onclick="deleteNote(${index})">Delete</button>
                <button onclick="shareTwitter('${note}')">Twitter</button>
                <button onclick="shareFacebook('${note}')">Facebook</button>
                <button onclick="shareWhatsApp('${note}')">WhatsApp</button>
            `;
    notesList.appendChild(li);
}

function deleteNote(index) {
    let notes = JSON.parse(localStorage.getItem("notes") || "[]");
    notes.splice(index, 1);
    localStorage.setItem("notes", JSON.stringify(notes));
    document.getElementById("notesList").innerHTML = "";
    loadNotes();
}

function shareTwitter(note) {
    const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(note)}`;
    window.open(url, "_blank");
}

function shareFacebook(note) {
    const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(note)}`;
    window.open(url, "_blank");
}

function shareWhatsApp(note) {
    const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(note)}`;
    window.open(url, "_blank");
}
