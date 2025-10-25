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
<!-- News Section -->
<div class="post-container">
  <div class="headline">
    <h2><?php echo $preview->title;?></h2>
    <div class="float-right"><p>By <?php echo $preview->author;?> | <?php echo $preview->date;?></p></div>
    <h3><?php echo $preview->subtitle;?></h3>
    <p>Tags: <?php echo $preview->tags; ?></p>
    <h3>USER ID:<?php echo $preview->user_id;?></h3>

  <!-- Läs Senare Widget? -->
   <?php
   use app\widgets\ReadWidget;
   echo ReadWidget::renderButton($preview->id);
   ?>

     <div class="latest-games">
        <?= include __DIR__ . 'partials/latest_games.php';?>
    </div>
    <div class="recommended">
        <?= include __DIR__ . 'partials/recommended.php';?>
    </div>
  <!-- End Läs Senare Widget -->

  </div>

  <!-- Share Widget --> <!-- End Share Widget -->
  <!-- Review Widget --> <!-- End Review Widget -->
  <!-- Comment Widget --> <!-- End Comment Widget -->

  <div class="main-content">
    <p><?php echo nl2br($preview->content);?></p>
  </div>
  <div class="img">
    <img src="<?php echo $preview->media;?>" alt="News Image" style="max-width: 400px; height: auto;">
  </div>

  <!-- Comments Section -->
<div>
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
      <input type="hidden" name="id" value="<?php echo $preview->id; ?>">
      <input type="hidden" name="user_id" value="<?php echo $preview->user_id; ?>">
      <br><button type="submit">Submit</button>
    </form>
  <?php else: ?>
    <p><a href="/login">Log in</a> to leave a comment.</p>
  <?php endif; ?>

</div>
<!-- End Comments Section -->

</div>
<!-- End News Section -->

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
