<h2>Reviews</h2>
<ul>
  <?php foreach ($reviews as $review):?>
  <li>
    <?php echo $review->title; ?>
    <?php echo $review->subtitle; ?>
    <?php echo $review->content; ?>
    <?php echo $review->date; ?>
    <?php echo $review->author; ?>
    <?php echo $review->category; ?>
    <?php echo $review->genre;?>
    <?php echo $review->media;?>
    <?php echo $review->platform;?>
    <?php echo $review->status; ?>
    <?php echo $review->tags;?>
  </li>
  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <li>
      <a href="/review/edit/<?php echo $review->id;?>">Edit Review</a>
      <!-- Delete Review -->
      <form action="/review/delete/<?php echo $review->id;?>" method="post">
        <input type="hidden" name="id" id="" value="<?php echo $review->id;?>">
        <button type="submit">Delete Review</button>
      </form>
    </li>
  <?php endif;?>
  <?php endforeach; ?>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <a href="/review/create">Create Review</a>
  <?php endif;?>
</ul>
</ul>
