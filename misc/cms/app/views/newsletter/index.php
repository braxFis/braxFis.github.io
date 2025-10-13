<h2>Newsletters</h2>
<ul>
    <?php foreach ($newsletters as $newsletter): ?>
        <li>
            <pre><?= var_export($newsletter, true) ?></pre>
            <a href="/newsletter/send/<?= $newsletter->id ?>" onclick="return confirm('Send this newsletter to all subscribers?')">Send</a>
            <strong><?php echo $newsletter->title;?></strong>
            <strong><?php echo $newsletter->body;?></strong>
            <img src="<?php echo $newsletter->image;?>" alt="<?php echo $newsletter->title;?>"/>
        </li>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li>
            <a href="/newsletter/edit/<?php echo $newsletter->id; ?>">Edit</a>
            <!-- Delete Newsletter -->
            <form action="/newsletter/delete/<?php echo $newsletter->id; ?>" method="post">
                <input type="hidden" name="id" id="" value="<?php echo $newsletter->id; ?>">
                <button type="submit">Delete</button>
            </form>
        </li>
    <?php endif;?>
    <?php endforeach;?>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="/newsletter/create/">Create Newsletter</a>
    <?php endif;?>
</ul>