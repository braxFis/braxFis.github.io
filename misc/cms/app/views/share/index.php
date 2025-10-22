<section class="share-container">
    <?php foreach ($shares as $share): ?>
    <div class="share-column">
        <h1><?= $share->name ?></h1>
        <p><?= $share->description ?></p>
        <a href="<?= $share->link ?>"><?= $share->link ?></a>
    </div>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <li>
            <a href="/feature/share/edit/<?php echo $share->id;?>">Edit</a>
            <!-- Delete Chapter -->
            <form action="/feature/share/delete/<?php echo $share->id;?>" method="POST">
                <input type="hidden" name="id" id="" value="<?php echo $share->id;?>">


                <button type="submit">Delete Share</button>
            </form>
        </li>
    <?php endif;?>
<?php endforeach; ?>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <a href="/feature/share/create/">Create Share</a>
<?php endif; ?>