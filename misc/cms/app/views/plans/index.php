<h2>Plans</h2>
<ul>
  <?php foreach($plans as $plan):?>
    <li>
      <div class="add-border">

        <label for="name">Name</label>
        <p><?= $plan->name ?></p>

        <label for="email">Email</label>
        <p><?= $plan->email?></p>

        <label for="phone">Phone</label>
        <p><?= $plan->phone ?></p>

        <label for="address">Address</label>
        <p><?= $plan->address?></p>

        <label for="type">Type</label>
          <p><?= $plan->type ?></p>
      </div>
    </li>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'):?>
      <li>
        <button><a href="/plan/edit/<?php echo $plan->id;?>">Edit Plan</a></button>
        <!-- Delete Plan-->
        <form action="/plan/delete/<?php echo $plan->id;?>" method="POST">
          <input type="hidden" name="id" id="name" value="<?php echo $plan->id;?>">
          <button type="submit">Delete Plan</button>
        </form>
      </li>
    <?php endif;?>
  <?php endforeach;?>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'):?>
    <button><a href="/plan/create">Create Plan</a></button>
  <?php endif;?>
</ul>
