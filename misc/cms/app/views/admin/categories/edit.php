<div>
    <form action="/categories/update/<?php echo $category->id;?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $category->id;?>">
        <div style="margin-bottom: 20px;">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($category->name, ENT_QUOTES);?>" required>
        </div>
        <!-- Populate categories dropdown -->
        <div>
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id">
                <?php foreach($categories as $category){?>
                    <option value="<?php echo $category->id; ?>"><?php echo $category->id == $post->category_id ? 'selected' : '';?></option>
                    <?php echo $category->name;?>
                <?php } ?>
            </select>
        </div>
        <button type="submit">Update Category</button>
    </form>
</div>
<?php