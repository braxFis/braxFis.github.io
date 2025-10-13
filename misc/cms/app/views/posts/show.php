<div>
    <?php if($post):?>
        <div class="post-preview">
            <div class="post-text">
                <img src="/uploads/<?= $post->image;?>" alt="test" width="200" class="float-image">
                <h2><?php echo htmlspecialchars($post->title, ENT_QUOTES); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($post->content, ENT_QUOTES)); ?></p>
            </div>
        </div>

    <?php else:?>
    <p>Post not found.</p>
    <?php endif; ?>
    <div>
        <h3>Comments</h3>
        <?php foreach ($comments as $comment): ?>
            <div style="margin-bottom: 20px;">
                <p><strong>User ID:</strong><?php echo htmlspecialchars($comment->user_id, ENT_QUOTES); ?></p>
                <p><strong>Comment date:</strong><?php echo htmlspecialchars($comment->created_at, ENT_QUOTES); ?></p>
                <p><?php echo nl2br(htmlspecialchars($comment->body, ENT_QUOTES)); ?></p>
            </div>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Comment form -->
            <form action="/comments/store" method="post" style="margin-top: 20px;">
                <label for="body">Comment:</label><br>
                <textarea name="body" id="body" cols="30" rows="5" required></textarea>
                <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $post->user_id; ?>">
                <br><button type="submit">Submit</button>
            </form>
        <?php else: ?>
            <p><a href="/login">Log in</a> to leave a comment.</p>
        <?php endif; ?>
    </div>
</div>
<style>
    .post-preview {
        overflow: auto;
        line-height: 1.6;
        font-family: sans-serif;
    }

    .float-image {
        margin-top: 20px;
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