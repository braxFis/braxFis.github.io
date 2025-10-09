<h2>News Indie Page</h2>
<div class="add-border">
  <ul>
    <li>
      <h6><?php echo $new->id;?></h6>
      <h6><?php echo $new->title;?></h6>
      <h6><?php echo $new->subtitle;?></h6>
      <h6><?php echo $new->content;?></h6>
      <h6><?php echo $new->date;?></h6>
      <h6><?php echo $new->author;?></h6>
    </li>
  </ul>
</div>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
  <li style="list-style-type: none">
    <button><a style="text-decoration: none; color: white" href="/news/edit/<?php echo $new->id;?>">Edit News</a></button>
    <!-- Delete Preview -->
    <form action="/news/delete/<?php echo $new->id;?>" method="post">
      <input type="hidden" name="id" id="" value="<?php echo $new->id;?>">
      <button type="submit">Delete News</button>
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
