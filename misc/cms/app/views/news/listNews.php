<h2>News Directory</h2>
<ul>
  <?php foreach ($news as $new):?>
    <li>
      <div class="add-border">
        <p>
          <a href="<?php echo "news/indie/" . $new->id;?>">
            <?php echo $new->title;?></a> |
          <span><?php echo $new->author;?></span> |
          <span><?php echo $new->date;?></span>
        </p>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
          <li style="list-style-type: none">
            <button><a style="text-decoration: none; color: white" href="/news/edit/<?php echo $new->id;?>">Edit News</a></button>
            <!-- Delete News -->
            <form action="/news/edit/<?php echo $new->id;?>" method="post">
              <input type="hidden" name="id" id="" value="<?php echo $new->id;?>">
            </form>
          </li>
        <?php endif;?>
      </div>
    </li>
  <?php endforeach;?>
</ul>
