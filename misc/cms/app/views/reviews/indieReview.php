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
