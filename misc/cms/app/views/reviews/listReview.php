<h2>Review Directory</h2>
<ul>
  <?php foreach ($reviews as $review):?>
  <li>
    <div class="add-border">
      <p>
        <a href="<?php echo "review/indie/" . $review->id;?>">
        <?php echo $review->title;?></a> |
        <span><?php echo $review->author;?></span> |
        <span><?php echo $review->date;?></span>
      </p>
      <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <li style="list-style-type: none">
          <button><a style="text-decoration: none; color: white" href="/review/edit/<?php echo $review->id;?>">Edit Review</a></button>
          <!-- Delete Review -->
          <form action="/review/edit/<?php echo $review->id;?>" method="post">
            <input type="hidden" name="id" id="" value="<?php echo $review->id;?>">
          </form>
        </li>
      <?php endif;?>
    </div>
  </li>
  <?php endforeach;?>
</ul>
