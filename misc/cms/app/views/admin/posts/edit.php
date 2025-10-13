<div>
    <?php if ($post == null): ?>
        <div>Post not found</div>
        <?php return; ?>
    <?php endif; ?>

    <form action="/posts/update/<?php echo $post->id; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $post->id; ?>">

        <div style="margin-bottom: 20px;">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post->title); ?>">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="content">Content</label>
            <textarea name="content" id="content" cols="30" rows="10"><?php echo htmlspecialchars($post->content, ENT_QUOTES); ?></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="image">Image</label>
            <input type="file" name="image" required>
        </div>
        <!-- Populate categories dropdown -->
        <div>
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id">
                <?php foreach($categories as $category){?>
                    <option value="<?php echo $category->id; ?> " <?php echo $category->id == $post->category_id ? 'selected' : '';?>>
                        <?php echo $category->name;?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit">Update Post</button>
    </form>
</div>
