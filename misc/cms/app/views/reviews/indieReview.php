<h2>Review Indie Page</h2>
<div class="add-border">
<ul>
  <li>
      <h6><?php echo $review->id;?></h6>
      <h6><?php echo $review->title;?></h6>
      <h6><?php echo $review->subtitle;?></h6>
      <h6><?php echo $review->content;?></h6>
      <h6><?php echo $review->date;?></h6>
      <h6><?php echo $review->author;?></h6>
      <h6><?php echo $review->category;?></h6>
      <h6><?php echo $review->genre;?></h6>
      <h6><?php echo $review->media;?></h6>
      <h6><?php echo $review->platform;?></h6>
      <h6><?php echo $review->status;?></h6>
      <h6><?php echo $review->tags;?></h6>
      <h6><?php echo $review->rating;?></h6>
  </li>
</div>
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
</ul>
<div>
  <h3>Comments</h3>
  <?php foreach ($comments as $comment): ?>
    <div style="margin-bottom: 20px;">
      <p><strong>User ID:</strong><?php echo htmlspecialchars($comment->user_id, ENT_QUOTES); ?></p>
      <p><strong>Comment date:</strong><?php echo htmlspecialchars($comment->created_at, ENT_QUOTES); ?></p>
      <p><?php echo nl2br(htmlspecialchars($comment->body, ENT_QUOTES)); ?></p>
    </div>
  <?php endforeach; ?>

  <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Comment form -->
    <form action="/comments/store" method="post" style="margin-top: 20px;">
      <label for="body">Comment:</label><br>
      <textarea name="body" id="body" cols="30" rows="5" required></textarea>
      <input type="hidden" name="id" value="<?php echo $new->id; ?>">
      <input type="hidden" name="user_id" value="<?php echo $new->user_id; ?>">
      <br><button type="submit">Submit</button>
    </form>
  <?php else: ?>
    <p><a href="/login">Log in</a> to leave a comment.</p>
  <?php endif; ?>

</div>
