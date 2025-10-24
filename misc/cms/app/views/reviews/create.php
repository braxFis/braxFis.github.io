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
    <label for="review_id">Review ID</label>
    <input type="number" name="review_id" value="<?= $review_id ?>">
    <label for="rating">Review Rating</label>
    <input type="text" name="rating" id="rating">
  </div>

  <div class="conclusion">
    <label for="conclusion">Conclusion</label>
    <textarea name="conclusion" id="conclusion" cols="30" rows="10">
      ...
    </textarea>
  </div>

  <div class="scorecard">
    <label for="graphics">Graphics</label>
    <label for="sound">Sound</label>
    <label for="duration">Duration</label>
    <label for="storyline">Storyline</label>
    <label for="gameplay">Gameplay</label>
  </div>

  <button type="submit">Create Review</button>
</form>
