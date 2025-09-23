<h2>Previews</h2>
<ul>
  <?php foreach ($previews as $preview):?>
  <li>
    <?php echo $preview->title; ?>
    <?php echo $preview->subtitle; ?>
    <?php echo $preview->content; ?>
    <?php echo $preview->date; ?>
    <?php echo $preview->author; ?>
    <?php echo $preview->category; ?>
    <?php echo $preview->genre;?>
    <?php echo $preview->media;?>
    <?php echo $preview->platform;?>
    <?php echo $preview->status; ?>
    <?php echo $preview->tags;?>
  </li>
  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <li>
      <a href="/preview/edit/<?php echo $preview->id;?>">Edit Preview</a>
      <!-- Delete Preview -->
      <form action="/preview/delete/<?php echo $preview->id;?>" method="post">
        <input type="hidden" name="id" id="" value="<?php echo $preview->id;?>">
        <button type="submit">Delete Preview</button>
      </form>
    </li>
  <?php endif;?>
  <?php endforeach; ?>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <a href="/preview/create">Create Preview</a>
  <?php endif;?>
</ul>
</ul>
