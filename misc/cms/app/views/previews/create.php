<h1>Create Preview</h1>
<form action="/preview/store" method="POST">
  <div>
    <label for="title">Preview Title</label>
    <input type="text" name="title" id="title">
    <label for="subtitle">Preview Subtitle</label>
    <input type="text" name="subtitle" id="subtitle">
    <label for="content">Preview Content</label>
    <textarea name="content" id="content" cols="30" rows="10"></textarea>
    <label for="date">Preview Date</label>
    <label for="author">Preview Author</label>
    <input type="text" name="author" id="author">
    <label for="genre">Preview Genre</label>
    <input type="text" name="genre" id="genre">
    <label for="media">Preview Media</label>
    <input type="text" name="media" id="media">
    <label for="platform">Preview Platform</label>
    <input type="text" name="platform" id="platform">
    <label for="status">Preview Status</label>
    <input type="text" name="status" id="status">
    <label for="tags">Preview Tags</label>
    <input type="text" name="tags" id="tags">
  </div>
  <button type="submit">Create Preview</button>
</form>
