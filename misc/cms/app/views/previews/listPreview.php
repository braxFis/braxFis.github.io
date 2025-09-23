<h2>Preview Directory</h2>
<ul>
  <?php foreach ($previews as $preview):?>
  <li>
    <div class="add-border">
      <p>
        <a href="<?php echo "preview/indie/" . $preview->id;?>">
        <?php echo $preview->title;?></a> |
        <span><?php echo $preview->author;?></span> |
        <span><?php echo $preview->date;?></span>
      </p>
      <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <li style="list-style-type: none">
          <button><a style="text-decoration: none; color: white" href="/preview/edit/<?php echo $preview->id;?>">Edit Preview</a></button>
          <!-- Delete Preview -->
          <form action="/preview/edit/<?php echo $preview->id;?>" method="post">
            <input type="hidden" name="id" id="" value="<?php echo $preview->id;?>">
          </form>
        </li>
      <?php endif;?>
    </div>
  </li>
  <?php endforeach;?>
</ul>
