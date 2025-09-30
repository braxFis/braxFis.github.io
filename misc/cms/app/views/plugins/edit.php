<form action="/plugins/update/<?php echo $plugin->id;?>" method="post">
  <input type="hidden" name="id" id="<?php echo $plugin->id;?>">
  <div>
    <label for="title">Plugin Title</label>
    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($plugin->title);?>">

    <label for="active">Plugin Status</label>
    <input type="number" name="active" id="active" value="<?php echo $plugin->active;?>">
  </div>

  <button type="submit">Update Plugin</button>
</form>
<?php
