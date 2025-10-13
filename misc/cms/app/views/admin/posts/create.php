<div>
    <div style="text-align: right; margin-bottom: 20px;">
        <a href="/posts" class="btn btn-primary">View Posts</a>
    </div>

    <h1>Create Post</h1>

    <form action="/posts/store" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-control" rows="10" required></textarea>
        </div>

        <div>
            <label for="image">Image</label>
            <input type="file" name="image" id="image">
        </div>

        <!-- Populate categories dropdown -->
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control">
                <?php foreach($categories as $category){ ?>
                    <option value="<?php echo $category->id; ?>">
                        <?php echo htmlspecialchars($category->name, ENT_QUOTES); ?>
                    </option>

                <?php } ?>
            </select>
        </div>

        <br/>

        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
</div>
