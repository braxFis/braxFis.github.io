<h1>Create News</h1>
<form action="/news/store" method="POST">
  <div>
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <label for="subtitle">Subtitle</label>
    <input type="text" name="subtitle" id="subtitle">
    <label for="content">Content</label>
    <input type="text" name="content" id="content">
    <label for="date">Date</label>
    <input type="text" name="date" id="date">
    <label for="author">Author</label>
    <input type="text" name="author" id="author">
    <label for="media">Media</label>
    <input type="text" name="media" id="Media">
    <label for="status">Status</label>
    <select name="status" id="status">
      <option value="draft">Draft</option>
      <option value="published">Published</option>
    </select>
    <label for="tags">Tags</label>
    <input type="text" name="tags" id="tags">
  </div>
  <button type="submit">Create News</button>
</form>
<?= (new \app\controllers\DragDropController())->editor(); ?>
