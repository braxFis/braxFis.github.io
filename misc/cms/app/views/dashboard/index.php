<html>
<body>

<div class="container">

  <div class="notification">
    <?php echo $notification->title;?>
    <?php echo $notification->date;?>
  </div>

  <div class="reviews">
    <h2>Reviews</h2>
    <ul>
      <?php foreach($reviews as $review):?>
      <?php if($review != null):?>
        <li><a href="<?php echo 'review/indie/' . $review->id;?>"><?php echo $review->title;?></a></li>
        <?php else:?>
        <?php echo "No reviews found";?>
      <?php endif; ?>
      <?php endforeach;?>
    </ul>
  </div>

  <div class="previews">
    <h2>Previews</h2>
    <ul>
      <?php foreach($previews as $preview):?>
        <li><a href="<?php echo 'preview/indie/' . $preview->id;?>"><?php echo $preview->title;?></a></li>
      <?php endforeach;?>
    </ul>
  </div>

  <div class="news">
    <h2>News</h2>
    <ul>
      <?php foreach($news as $new):?>
        <li><a href="<?php echo 'news/indie/' . $new->id;?>"><?php echo $new->title;?></a></li>
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
