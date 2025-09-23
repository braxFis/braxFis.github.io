<form action="/preview/update/<?php echo $preview->id;?>" method="POST">
  <input type="hidden" name="id" id="<?php echo $preview->id;?>">
  <div>
      <label for="title">Preview Title</label>
      <input type="text" name="title" id="title" value="<?php echo $preview->title;?>">
      <label for="subtitle">Preview Subtitle</label>
      <input type="text" name="subtitle" id="subtitle" value="<?php echo $preview->subtitle;?>">
      <label for="content">Preview Content</label>
      <textarea name="content" id="content" cols="30" rows="10"><?php echo $preview->content;?></textarea>
      <label for="date">Preview Date</label>
      <?php echo $preview->date;?>
      <label for="author">Preview Author</label>
      <input type="text" name="author" id="author" value="<?php echo $preview->author;?>">
      <label for="genre">Preview Genre</label>
      <input type="text" name="genre" id="genre" value="<?php echo $preview->genre;?>">
      <label for="media">Preview Media</label>
      <input type="text" name="media" id="media" value="<?php echo $preview->media;?>">
      <label for="platform">Prview Platform</label>
      <input type="text" name="platform" id="platform" value="<?php echo $preview->platform;?>">
      <label for="status">Preview Status</label>
      <input type="text" name="status" id="status" value="<?php echo $preview->status;?>">
      <label for="tags">Preview Tags</label>
      <input type="text" name="tags" id="tags" value="<?php echo $preview->tags;?>">
  </div>
  <button type="submit">Update Preview</button>
</form>
<?php
