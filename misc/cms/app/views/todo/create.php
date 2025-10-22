<h1>Create Todo</h1>

<form action="/feature/todo/store" method="POST">
<div id="canvas" data-slug="<?= htmlspecialchars($slug ?? '')?>">
  <!-- Canvas for drag-and-drop elements -->
  <label for="name">Name</label>
  <input type="text" name="name" id="name" required>
  
  <label for="type">Type</label>
  <input type="text" name="type" id="type" required>

  <label for="description">Description</label>
  <textarea name="description" id="description" required></textarea>

</div>

<button type="submit">Create Todo</button>

</form>