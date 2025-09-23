<form action="/news/update/<?php echo $new->id; ?>" method="POST">
  <input type="hidden" name="id" id="<?php echo $new->id;?>">
  <div>
    <label for="title">News Title</label>
    <input type="text" name="title" id="title" value="<?php echo $new->title;?>">
    <label for="subtitle">News Subtitle</label>
    <input type="text" name="subtitle" id="subtitle" value="<?php echo $new->subtitle;?>">
    <label for="content">News Content</label>
    <input type="text" name="content" id="content" value="<?php echo $new->content;?>">
    <label for="date">News Date</label>
    <input type="text" name="date" id="date" value="<?php echo $new->date;?>">
    <label for="author">News Author</label>
    <input type="text" name="author" id="author" value="<?php echo $new->author;?>">
    <label for="media">Media</label>
    <input type="text" name="media" id="media" value="<?php echo $new->media;?>">
    <label for="Status">Status</label>
    <select name="status" id="status">
      <option value="draft">Draft</option>
      <option value="published">Published</option>
    </select>
    <label for="tags">Tags</label>
    <input type="text" name="tags" id="tags" value="<?php echo $new->tags;?>">
  </div>
  <button type="submit">Update News</button>
</form>
