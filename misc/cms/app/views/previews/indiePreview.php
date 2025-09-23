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
