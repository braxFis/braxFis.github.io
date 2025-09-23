<h2>News Indie Page</h2>
<div class="add-border">
  <ul>
    <li>
      <h6><?php echo $new->id;?></h6>
      <h6><?php echo $new->title;?>/h6>
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
