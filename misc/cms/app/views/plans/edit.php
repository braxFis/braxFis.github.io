<form action="/plan/update/<?php echo $plan->id;?>" method="POST">
  <input type="hidden" name="id" id="<?php echo $plan->id;?>">
  <div>

    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="<?= $plan->name;?>">

    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="<?= $plan->email;?>">

    <label for="phone">Phone</label>
    <input type="number" name="phone" id="phone" value="<?= $plan->phone;?>">

    <label for="address">Address</label>
    <input type="text" name="address" id="address" value="<?= $plan->address;?>">

    <label for="type">Type</label>

    <input type="text" name="type" id="type" value="<?= $plan->type ?>">

    <button type="submit">Update Plan</button>
  </div>
</form>
