<div>
    <!-- Display session message-->
    <?php if(isset($_SESSION['message'])): ?>
        <div id="message" class="<?php echo strpos($_SESSION['message'], 'success') !== false ? 'alert-success' : 'alert-danger'; ?>'">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']);?>
        </div>
    <?php endif; ?>
    <div style="margin-top: 20px;">
        <a href="/posts/create" class="btn btn-primary">Create Post</a>
        <a href="/categories/create" class="btn btn-primary">Create Categories</a>
    </div>
</div>

<h1>Admin Posts</h1>
<?php foreach($posts as $post): ?>
<div class="post-preview" style="margin-top: 20px;">
    <div class="post-text">
        <img src="/uploads/<?php echo $post->image;?>" alt="<?php echo $post->title; ?>" width="200" class="float-image">
        <h3><?php echo htmlspecialchars($post->title, ENT_QUOTES); ?></h3>
        <p><?php echo htmlspecialchars($post->content, ENT_QUOTES); ?></p>
    </div>
    <div class="post-buttons">
        <a href="/posts/edit/<?php echo $post->id; ?>" class="btn btn-primary">Edit</a>
        <a href="/posts/show/<?php echo $post->id; ?>">View</a>

        <p><strong>Category:</strong><?php echo $post->category_name ? htmlspecialchars($post->category_name, ENT_QUOTES): 'No Category'; ?></p>
        <!-- Delete Post -->
        <form action="/posts/delete/<?php echo $post->id; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $post->id; ?>">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</div>
<?php endforeach; ?>

<style>
    .message-success{
        color: green;
    }

    .message-error{
        color: red;
    }

    .post-preview {
        overflow: auto;
        margin-bottom: 30px;
        line-height: 1.6;
        font-family: sans-serif;
    }

    .float-image {
        float: left;
        width: 250px;
        height: auto;
        margin-right: 20px;
        border-radius: 6px;
    }

    .post-text {
        overflow: hidden;
    }



</style>
