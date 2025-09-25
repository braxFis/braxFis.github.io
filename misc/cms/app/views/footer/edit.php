<form action="/footer/update/<?php echo $footer->id;?>" method="POST">
    <input type="hidden" name="id" id="<?php echo $footer->id; ?>">
    <div>
        <label for="name">Footer Label</label>
        <input type="text" name="label" id="label" value="<?php echo htmlspecialchars($footer->label, ENT_QUOTES);?>" required>

        <label for="name">Footer Url</label>
        <input type="text" name="url" id="url" value="<?php echo htmlspecialchars($footer->url, ENT_QUOTES);?>" required>

        <label for="name">Footer Sort Order</label>
        <input type="text" name="sort_order" id="sort_order" value="<?php echo htmlspecialchars($footer->sort_order, ENT_QUOTES);?>" required>

    </div>

    <button type="submit">Update Footer</button>
</form>
<?php
