<form action="/feature/share/update/<?php echo $share->id;?>" method="POST">
    <input type="hidden" name="id" id="<?php echo $share->id; ?>">
    <div>
        <label for="name">Share Name</label>
        <input type="text" name="name" id="name" value="<?php echo $share->name ?>" required>

        <label for="description">Share Content</label>
        <?php if (!empty($share->description)): ?>
            <input type="hidden" name="description" value="<?php echo $share->description; ?>">
        <?php endif; ?>
        <textarea cols="30" rows="30" name="description" id="description" value="<?php echo $share->description;?>">
            <?php echo $share->description;?>
        </textarea>

        <label for="link">Share Link</label>
        <input type="text" name="link" id="link" value="<?php echo $share->link;?>" required>
    </div>

    <button type="submit">Update Share</button>
</form>
<?php
