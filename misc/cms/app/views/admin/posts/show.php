<?php if (!$post): ?>
    <p>❌ Post not found.</p>
    <?php return; ?>
<?php endif; ?>

<div>
    <!-- View all Post button -->
    <div style="text-align: right; margin-bottom: 20px;">
        <a href="/posts">View Posts</a>
    </div>
    <h2><?php echo htmlspecialchars($post->title, ENT_QUOTES);?></h2>
    <h2><?php echo htmlspecialchars($post->content, ENT_QUOTES);?></h2>
    <p><?php echo htmlspecialchars($post->category_name, ENT_QUOTES); ?></p>
    <img src="/uploads/<?php echo $post->image;?>" alt="<?php echo $post->title; ?>" width="200" class="float-image">
    <?php if(!empty($comments)): ?>
    <h3>Comments</h3>¶
    <?php foreach($comments as $comment): ?>
    <div>
        <p><?php echo htmlspecialchars($comment->content, ENT_QUOTES); ?></p>
        <a href="/admin/comments/edit/<?php echo $comment->id; ?>" class="btn btn-warning btn-sm">Edit</a>
        <form action="/admin/comments/delete/<?php echo $comment->id; ?>" method="post" style="display: inline">
            <input type="hidden" name="_method" id="" value="DELETE">
            <button class="btn btn-danger btn-sm" type='submit'>Delete</button>
        </form>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p>No comments yet.</p>
    <?php endif; ?>
</div>