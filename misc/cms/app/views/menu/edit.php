<form action="/menu/update/<?php echo $menu->id;?>" method="POST">
    <input type="hidden" name="id" id="<?php echo $menu->id; ?>">
    <div>
        <label for="name">Menu Label</label>
        <input type="text" name="label" id="label" value="<?php echo htmlspecialchars($menu->label, ENT_QUOTES);?>" required>

        <label for="name">Menu Url</label>
        <input type="text" name="url" id="url" value="<?php echo htmlspecialchars($menu->url, ENT_QUOTES);?>" required>

        <label for="name">Menu Sort Order</label>
        <input type="text" name="sort_order" id="sort_order" value="<?php echo htmlspecialchars($menu->sort_order, ENT_QUOTES);?>" required>

    </div>

    <button type="submit">Update Menu</button>
</form>
<?php
