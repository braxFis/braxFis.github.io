<html>

<body>

<div class="container">
  <div class="reviews">
    <h2>Latest Reviews</h2>
    <ul>
      <?php foreach($reviews as $review):?>
      <?php if($review != null):?>
        <li><a href="<?php echo '#';?>"><?php echo $review->title;?></a></li>
        <?php else:?>
        <?php echo "No reviews found";?>
      <?php endif; ?>
      <?php endforeach;?>
    </ul>
  </div>

  <div class="previews">
    <h2>Latest Previews</h2>
    <ul>
      <?php foreach($previews as $preview):?>
      <?php if(!empty($preview)):?>
        <li><a href="<?php echo '#';?>"><?php echo $preview->title;?></a></li>
      <?php else: ?>
      <?php echo "No previews found";?>
      <?php endif; ?>
      <?php endforeach;?>
    </ul>
  </div>

  <div class="news">
    <h2>Latest News</h2>
    <ul>
      <?php foreach($news as $new):?>
        <li><a href="<?php echo '#';?>"><?php echo $new->title;?></a></li>
      <?php endforeach;?>
    </ul>
  </div>
</div>

<div class="charts">
  <h2>Charts</h2>
  <?php require __DIR__ . '/../../views/statistics/index.php';?>
</div>

</body>
</html>
