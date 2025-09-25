<section class="page-container">
    <?php foreach ($pages as $page): ?>
    <div class="page-column">
        <h1>Page</h1>
        <h2><?= htmlspecialchars($page->title) ?></h2>
        <p><?= nl2br(htmlspecialchars($page->slug)) ?></p>
        <p><?= htmlspecialchars($page->content);?></p>
    </div>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <li>
            <a href="/page/edit/<?php echo $page->id;?>">Edit</a>
            <!-- Delete Chapter -->
            <form action="/page/delete/<?php echo $page->id;?>" method="POST">
                <input type="hidden" name="id" id="" value="<?php echo $page->id;?>">
                <button type="submit">Delete Page</button>
            </form>
        </li>
    <?php endif;?>
<?php endforeach; ?>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <a href="/page/create/">Create Page</a>
<?php endif; ?>
