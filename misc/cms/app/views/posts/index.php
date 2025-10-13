<div>
    <?php foreach ($posts as $post): ?>
        <div style="margin-bottom: 20px;">
            <h2><?php echo htmlspecialchars($post->title, ENT_QUOTES); ?></h2>
            <p><?php echo htmlspecialchars($post->content, ENT_QUOTES); ?></p>
        </div>
    <div>
        <a href="/posts/show/<?php echo $post->id; ?>">View Post</a>
    </div>
<?php endforeach; ?>
</div>
