<div>
    <div style="text-align: right; margin-bottom: 20px;">
        <a href="/categories/create">Create Category</a>
        <!-- View Posts Button -->
        <a href="/posts">View Posts</a>
    </div>

    <h1>Admin Categories</h1>
    <?php foreach ($categories as $category): ?>
    <div style="margin-bottom: 20px; margin-top: 20px;">
        <div>
            <h2><?php echo htmlspecialchars($category->name, ENT_QUOTES); ?></h2>
            <a href="/categories/edit/<?php echo $category->id; ?>">Edit</a>
            <a href="/categories/posts/<?php echo $category->id;?>">View Posts</a>
            <!-- Delete Category -->
            <form action="/categories/delete/<?php echo $category->id; ?>" method="POST">
                <input type="hidden" name="_method" id="" value="DELETE">
                <button type="submit">Delete</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php
