<section class="todo-container">
    <?php foreach ($todos as $todo): ?>
    <div class="todo-column">
        <h1><?= $todo->name ?></h1>
        <p><?= $todo->description ?></p>
    </div>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <li>
            <a href="/feature/todo/edit/<?php echo $todo->id;?>">Edit</a>
            <!-- Delete Chapter -->
            <form action="/feature/todo/delete/<?php echo $todo->id;?>" method="POST">
                <input type="hidden" name="id" id="" value="<?php echo $todo->id;?>">
                <button type="submit">Delete Todo</button>
            </form>
        </li>
    <?php endif;?>
<?php endforeach; ?>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <a href="/feature/todo/create/">Create Todo</a>
<?php endif; ?>