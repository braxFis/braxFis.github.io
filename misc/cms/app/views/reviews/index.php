<h2>Reviews</h2>
<ul>
  <?php foreach ($reviews as $review):?>
  <li style="list-style-type: none">
    <div class="add-border">
      <label for="title">Title</label>
      <p><?php echo $review->title; ?></p>
      <label for="subtitle">Subtitle</label>
      <p><?php echo $review->subtitle; ?></p>
      <label for="content">Content</label>
      <p><?php echo $review->content; ?></p>
      <label for="date">Date</label>
      <p><?php echo $review->date; ?></p>
      <label for="author">Author</label>
      <p><?php echo $review->author; ?></p>
      <label for="category">Category</label>
      <!--<p><?php //echo $review->category; ?></p>-->
      <label for="Genre">Genre</label>
      <p><?php echo $review->genre;?></p>
      <label for="Media">Media</label>
      <p><?php echo $review->media;?></p>
      <label for="Platform">Platform</label>
      <p><?php echo $review->platform;?></p>
      <label for="Status">Status</label>
      <p><?php echo $review->status; ?></p>
      <label for="Tags">Tags</label>
      <p><?php echo $review->tags;?></p>
      <label for="Rating">Rating</label>
      <p><?php echo $review->rating;?></p>
    </div>
  </li>
  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <li style="list-style-type: none">
      <button><a style="text-decoration: none; color: white" href="/review/edit/<?php echo $review->id;?>">Edit Review</a></button>
      <!-- Delete Review -->
      <form action="/review/delete/<?php echo $review->id;?>" method="post">
        <input type="hidden" name="id" id="" value="<?php echo $review->id;?>">
        <button type="submit">Delete Review</button>
      </form>
    </li>
  <?php endif;?>
  <?php endforeach; ?>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <button><a style="text-decoration: none; color: white;" href="/review/create">Create Review</a></button>
  <?php endif;?>
</ul>
</ul>
