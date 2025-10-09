<h2>Preview Indie Page</h2>
<div class="add-border">
<ul>
  <li>
      <h6><?php echo $preview->id;?></h6>
      <h6><?php echo $preview->title;?></h6>
      <h6><?php echo $preview->subtitle;?></h6>
      <h6><?php echo $preview->content;?></h6>
      <h6><?php echo $preview->date;?></h6>
      <h6><?php echo $preview->author;?></h6>
      <h6><?php echo $preview->category;?></h6>
      <h6><?php echo $preview->genre;?></h6>
      <h6><?php echo $preview->media;?></h6>
      <h6><?php echo $preview->platform;?></h6>
      <h6><?php echo $preview->status;?></h6>
      <h6><?php echo $preview->tags;?></h6>
  </li>
</div>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
  <li style="list-style-type: none">
    <button><a style="text-decoration: none; color: white" href="/preview/edit/<?php echo $preview->id;?>">Edit Preview</a></button>
    <!-- Delete Preview -->
    <form action="/preview/delete/<?php echo $preview->id;?>" method="post">
      <input type="hidden" name="id" id="" value="<?php echo $preview->id;?>">
      <button type="submit">Delete Preview</button>
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
