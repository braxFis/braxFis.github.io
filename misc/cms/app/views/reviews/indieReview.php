<style>

  *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;

  }
  .post-container {
    border: 2px solid #ccc;
    padding: 20px;
    margin: 20px 0;
    border-radius: 10px;
    background-color: #f9f9f9;
  }

  .headline{
    display: grid;
    grid-template-columns: repeat(2, 100px);
  }

  .float-right{
    width: 200px;
  }

  .headline h2{
    color: green;
    font-size: 40px !important;
  }

  .headline h3{
    color: orange;
    font-size: 20px;
  }

  .headline p{
    color: gray;
    font-style: italic;
    font-size: 14px;
  }

  .main-content p {
    font-size: 16px;
    line-height: 1.6;
    color: darkblue;
  }

  .headline {
    border-bottom: 2px solid #ccc;
    padding-bottom: 10px;
    margin-bottom: 20px;
  }
  .main-content {
    margin-bottom: 20px;
  }
  .img {
    text-align: center;
    margin-bottom: 20px;
  }

  .main-content{
    width: 700px;
  }

  .headline p{
    background-color: red;
    padding: 20px;
    border-radius: 10px;
    font-size: 30px;
    font-weight: bold;
    width: max-content;
    color: white;
    margin:10px;
  }
</style>
<h2>Review Indie Page</h2>
<div class="post-container">
  <div class="headline">
    <h2><?= $review->title ?></h2>
    <div><p><?php echo $review->author;?> | <?php echo $review->date ?></p></div>
    <h3><?= $review->subtitle?></h3>

    <div class="extra">
      <h6><?php echo $review->category;?></h6>
      <h6><?php echo $review->genre;?></h6>
      <h6><?php echo $review->platform;?></h6>
      <h6><?php echo $review->status;?></h6>
      <h6><?php echo $review->tags;?></h6>
    </div>

    <div class="main-content">
      <h6><?php echo $review->content;?></h6>
    </div>

    <div class="score-container">
      <h6><?php echo $review->rating;?></h6>
    </div>

    <h6><?php echo $review->media;?></h6>

  </div>
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
