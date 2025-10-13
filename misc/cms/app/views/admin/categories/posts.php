
<div>
    <h2>Posts in Category: <?php echo htmlspecialchars($category->name, ENT_QUOTES); ?></h2>

    <?php if (!empty($posts)): ?>
        <ul>
            <?php foreach($posts as $post): ?>
                <li>
                    <h3><?php echo htmlspecialchars($post->title, ENT_QUOTES); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($post->content, ENT_QUOTES)); ?></p>
                    <p><?php echo htmlspecialchars($post->category_name, ENT_QUOTES); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No posts found.</p>
    <?php endif; ?>
</div>
