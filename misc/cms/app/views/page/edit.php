<form action="/page/update/<?php echo $page->id;?>" method="POST">
    <input type="hidden" name="id" id="<?php echo $page->id; ?>">
    <div>
        <label for="title">Page Name</label>
        <input type="text" name="title" id="title" value="<?php echo $page->title;?>" required>

        <label for="slug">Page Slug</label>
        <input type="text" name="slug" id="slug" value="<?php echo $page->slug;?>">

        <label for="content">Page Content</label>
        <?php if (!empty($page->content)): ?>
            <input type="hidden" name="content" value="<?php echo $page->content; ?>">
        <?php endif; ?>
        <input type="text" name="content" id="content" required>
    </div>

    <button type="submit">Update Page</button>
</form>
<?php
