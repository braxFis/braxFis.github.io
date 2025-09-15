<form action="/review/update/<?php echo $review->id;?>" method="POST">
  <input type="hidden" name="id" id="<?php echo $review->id;?>">
  <div>
      <label for="title">Review Title</label>
      <input type="text" name="title" id="title" value="<?php echo $review->title;?>">
      <label for="subtitle">Review Subtitle</label>
      <input type="text" name="subtitle" id="subtitle" value="<?php echo $review->subtitle;?>">
      <label for="content">Review Content</label>
      <textarea name="content" id="content" cols="30" rows="10"><?php echo $review->content;?></textarea>
      <label for="date">Review Date</label>
      <?php echo $review->date;?>
      <label for="author">Review Author</label>
      <input type="text" name="author" id="author" value="<?php echo $review->author;?>">
      <label for="genre">Review Genre</label>
      <input type="text" name="genre" id="genre" value="<?php echo $review->genre;?>">
      <label for="media">Review Media</label>
      <input type="text" name="media" id="media" value="<?php echo $review->media;?>">
      <label for="platform">Review Platform</label>
      <input type="text" name="platform" id="platform" value="<?php echo $review->platform;?>">
      <label for="status">Review Status</label>
      <input type="text" name="status" id="status" value="<?php echo $review->status;?>">
      <label for="tags">Review Tags</label>
      <input type="text" name="tags" id="tags" value="<?php echo $review->tags;?>">
  </div>
  <button type="submit">Update Review</button>
</form>
<?php
