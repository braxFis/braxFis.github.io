<?php foreach($plugins as $plugin):?>
<div class="plugins-column">
  <h1>Plugins</h1>
  <h2><?= $plugin->id?></h2>
  <h2><?= $plugin->name?></h2>
  <h3><?= $plugin->active?></h3>
</div>
  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <li>
      <a href="/plugins/edit/<?php echo $plugin->id;?>">Edit</a>
      <!-- Delete Chapter -->
      <form action="/plugins/inactivate/<?php echo $plugin->id;?>" method="POST">
        <input type="hidden" name="id" id="" value="<?php echo $plugin->id;?>">
        <button type="submit">Inactivate Plugin</button>
      </form>
    </li>
  <?php endif;?>
<?php endforeach;?>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
  <a href="/plugins/install/">Install Plugin</a>
<?php endif; ?>
