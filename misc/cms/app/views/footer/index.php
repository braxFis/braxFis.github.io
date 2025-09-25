<h2>Footer Edit</h2>
<footer>
<div class="footer-container">
<?php foreach ($footers as $footer): ?>
<div class="footer-column">
    <h1>TBA</h1>
        <li>
                <a href="<?php echo $footer->url;?>">
                    <?php echo $footer->label;?>
                </a>
        </li>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
            <li>
                <a href="/footer/edit/<?php echo $footer->id;?>">Edit</a>
                <!-- Delete Footer -->
                <form action="/footer/delete/<?php echo $footer->id;?>" method="POST">
                    <input type="hidden" name="id" id="" value="<?php echo $footer->id;?>">
                    <button type="submit">Delete Footer</button>
                </form>
            </li>
        <?php endif;?>
</div>
<?php endforeach; ?>
<ul>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <a href="/footer/create/">Create Footer</a>
    <?php endif; ?>
</ul>
</div>
</footer>