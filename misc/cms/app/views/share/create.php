<h1>Create Share</h1>

<form action="/feature/share/store" method="POST">
<div id="canvas" data-slug="<?= htmlspecialchars($slug ?? '')?>">
  <!-- Canvas for drag-and-drop elements -->
  <label for="name">Name</label>
  <input type="text" name="name" id="name" required>
  
  <label for="link">Link</label>
  <input type="text" name="link" id="name" required>

  <label for="description">Description</label>
  <textarea name="description" id="description" required></textarea>

  <label for="post_id">Post ID</label>
  <input type="text" name="post_id" id="post_id" required>
</div>

<button type="submit">Create Share</button>

</form>