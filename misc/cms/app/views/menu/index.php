<h2>Menu Edit</h2>
<footer>
<div class="menu-container">
<?php foreach ($menus as $menu): ?>
<div class="menu-column">
        <li>
                <a href="<?php echo $menu->url;?>">
                    <?php echo $menu->label;?>
                </a>
        </li>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
            <li>
                <a href="/menu/edit/<?php echo $menu->id;?>">Edit</a>
                <!-- Delete Footer -->
                <form action="/menu/delete/<?php echo $menu->id;?>" method="POST">
                    <input type="hidden" name="id" id="" value="<?php echo $menu->id;?>">
                    <button type="submit">Delete Menu</button>
                </form>
            </li>
        <?php endif;?>
</div>
<?php endforeach; ?>
<ul>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <a href="/menu/create/">Create Menu</a>
    <?php endif; ?>
</ul>
</div>
</footer>