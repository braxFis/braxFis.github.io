<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <title></title>
<style>
    @media (max-width: 600px) {
        .post-item {
            flex-direction: column;
        }

        .post-image {
            width: 100%;
            height: auto;
        }

        .post-content {
            padding: 15px;
        }
    }

    .post-item {
        display: flex;
        background-color: #1c1c1c;
        margin: 20px 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        color: #fff;
    }

    .post-image {
        width: 200px;
        height: 150px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .post-content {
        padding: 15px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .post-title {
        margin: 0;
        font-size: 20px;
        color: #ffcc00;
    }

    .post-snippet {
        margin: 10px 0;
        color: #ccc;
        font-size: 14px;
    }

    .post-readmore {
        align-self: flex-start;
        color: #ffcc00;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }

    .post-readmore:hover {
        text-decoration: underline;
    }

    .main-footer {
        background-color: #1c1c1c;
        color: #aaa;
        text-align: center;
        padding: 20px 0;
        font-size: 14px;
        border-top: 1px solid #333;
        margin-top: 60px;
    }

    .main-footer a {
        color: #ffcc00;
        text-decoration: none;
    }

    .main-footer a:hover {
        color: white;
    }

    .main-footer li{
        list-style-type: none;
    }
    .main-menu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #1c1c1c;
        padding: 15px 30px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    /*.main-menu ul {*/
    /*    list-style: none;*/
    /*    margin: 0;*/
    /*    padding: 0;*/
    /*    display: flex;*/
    /*    gap: 20px;*/
    /*}*/

    /*.main-menu ul li {*/
    /*    display: inline;*/
    /*}*/

    /*.main-menu ul li a {*/
    /*    text-decoration: none;*/
    /*    color: #ffcc00;*/
    /*    font-weight: bold;*/
    /*    font-size: 16px;*/
    /*    padding: 8px 12px;*/
    /*    border-radius: 6px;*/
    /*    transition: background 0.2s ease;*/
    /*}*/

    /*.main-menu ul li a:hover {*/
    /*    background: rgba(255, 204, 0, 0.2);*/
    /*    color: #fff;*/
    /*}*/

    .search-bar {
        display: flex;
        gap: 10px;
    }

    .search-bar input[type="search"] {
        padding: 5px;
    }

    .search-bar button {
        padding: 5px 10px;
        cursor: pointer;
    }
    form {
        max-width: 400px;
        margin: 2rem auto;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: #fff;
        font-family: Arial, sans-serif;
    }

    label {
        display: block;
        margin: 1rem 0 0.5rem;
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        transition: border 0.2s ease-in-out;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #007bff;
        outline: none;
    }

    button {
        display: inline-block;
        background-color: #007bff;
        color: #fff;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }
    .about-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
    }
    .about-column {
        flex: 1;
        min-width: 280px;
        max-width: 32%;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    .about-column img {
        max-width: 100%;
        height: auto;
    }
    .gallery-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .gallery-thumb {
        max-width: 150px;
        max-height: 100px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #ccc;
    }

.menu-prime{
    display: flex;
    justify-content: center;
    align-items: center;
    background: #1c1c1c;
    padding: 15px 30px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    gap: 10px;
}
.menu-prime button{
    background: linear-gradient(135deg, rgb(0, 123, 255), rgb(123, 0, 255));
}

.menu-prime button a{
    color: white;
    list-style-type: none;
    text-decoration: none;
}

.menu-prime button:hover{
    background: linear-gradient(135deg, rgb(0, 20, 40), rgb(60, 80, 100));
}

    .search-container {
        /*display: flex;*/
        /*justify-content: center;*/
        /*align-items: center;*/
        max-width: 800px;
        width: 90%;
        /*background: linear-gradient(135deg, rgb(0, 123, 255), rgb(123, 0, 255));*/
        border-radius: 50px;
        padding: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .issue-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }

    .issue-card {
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #f9f9f9;
    }

    .pagination {
        margin-top: 1rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        align-items: center;
    }

    .container{
      display: flex;
    }
    .reviews{}

    .reviews li{
      list-style-type: none
    }

    .add-border li{
      list-style-type: none
    }
    .previews{}
    .news{}


body {
  /* Prevent the user selecting text in the example */
  user-select: none;
}

#draggable {
  text-align: center;
  background: white;
}

.dropzone {
  width: max-width;
  height: max-height;
  background: blueviolet;
  margin: 10px;
  padding: 10px;
}

.dropzone.dragover {
  background-color: purple;
}

.dragging {
  opacity: 0.5;
}

</style>
</head>
<!--<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>-->
<script src="https://cdn.tiny.cloud/1/19srbsosgvqyagoc7x8ztcytwjhd2vwnqbz8ql6cdpj3t72a/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#content',
    height: 400,
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
      'searchreplace', 'visualblocks', 'code', 'fullscreen',
      'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic backcolor | \
    alignleft aligncenter alignright alignjustify | \
    bullist numlist outdent indent | removeformat | help'
  });
</script>

<button id="resetBtn">Återställ</button>

<div class="menu-prime dropzone">
  <button style="background: blue;color:black;"class="menu-prime" id="draggable" draggable="true"><a style="color:black;"href="/login">Buttface</a></button>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<nav class="menu-prime dropzone" id="drop-target"> <!--Add DnD tags here -->
        <!--<img src="/uploads/logo.png" alt="logowork.." width="75" height="75">-->
        <button><a href="/">Home</a></button>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <button><a href="/login">Login</a></button>
        <button><a href="/media">Media</a></button>
        <?php endif; ?>
</nav>
<!--<script type="module" src="/index.js"></script>-->
<script>
// 🧹 Reset-knapp
document.getElementById('resetBtn').addEventListener('click', () => {
  fetch('reset_position.php', { method: 'POST' })
    .then(() => location.reload());
});
let dragged;

// Hämta sparad position från servern vid laddning
window.addEventListener('load', () => {
    fetch('get_position.php')
        .then(response => response.json())
        .then(data => {
            if (data.dropzoneId) {
                const draggedElem = document.getElementById("draggable");
                const targetElem = document.getElementById(data.dropzoneId);
                if (draggedElem && targetElem) {
                    targetElem.appendChild(draggedElem);
                }
            }
        });
});

/* events fired on the draggable target */
const source = document.getElementById("draggable");
source.addEventListener("dragstart", (event) => {
    dragged = event.target;
    event.target.classList.add("dragging");
});

source.addEventListener("dragend", (event) => {
    event.target.classList.remove("dragging");
});

/* events fired on the drop targets */
const target = document.getElementById("drop-target");
target.addEventListener("dragover", (event) => event.preventDefault());
target.addEventListener("drop", (event) => {
    event.preventDefault();
    if (event.target.classList.contains("dropzone")) {
        event.target.appendChild(dragged);

        // Skicka ny position till servern
        fetch('save_position.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({elementId: dragged.id, dropzoneId: event.target.id})
        });
    }
});
</script>