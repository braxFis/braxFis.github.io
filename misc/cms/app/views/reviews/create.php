<h1>Create Review</h1>
<form action="/review/store" method="POST">
  <div>
    <label for="title">Review Title</label>
    <input type="text" name="title" id="title">
    <label for="subtitle">Review Subtitle</label>
    <input type="text" name="subtitle" id="subtitle">
    <label for="content">Review Content</label>
    <textarea name="content" id="content" cols="30" rows="10"></textarea>
    <label for="date">Review Date</label>
    <label for="author">Review Author</label>
    <input type="text" name="author" id="author">
    <label for="genre">Review Genre</label>
    <input type="text" name="genre" id="genre">
    <label for="media">Review Media</label>
    <input type="text" name="media" id="media">
    <label for="platform">Review Platform</label>
    <input type="text" name="platform" id="platform">
    <label for="status">Review Status</label>
    <input type="text" name="status" id="status">
    <label for="tags">Review Tags</label>
    <input type="text" name="tags" id="tags">
    <label for="rawg">RAWG Game ID</label>
    <input type="text" name="rawg" id="rawg" value="">
  </div>
  <button type="submit">Create Review</button>
</form>
