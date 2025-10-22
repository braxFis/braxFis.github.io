<form action="/feature/todo/update/<?php echo $todo->id;?>" method="POST">
    <input type="hidden" name="id" id="<?php echo $todo->id; ?>">
    <div>
        <label for="name">Todo Name</label>
        <input type="text" name="name" id="name" value="<?php echo $todo->name ?>" required>

        <label for="description">Todo Content</label>
        <?php if (!empty($todo->description)): ?>
            <input type="hidden" name="description" value="<?php echo $todo->description; ?>">
        <?php endif; ?>
        <textarea cols="30" rows="30" name="description" id="description" value="<?php echo $todo->description;?>">
            <?php echo $todo->description;?>
        </textarea>

        <label for="type">Todo Type</label>
        <input type="text" name="type" id="type" value="<?php echo $todo->type;?>" required>
    </div>

    <button type="submit">Update Share</button>
</form>
<?php
