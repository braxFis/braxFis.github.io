<form action="/page/update/<?php echo $page->id;?>" method="POST">
    <input type="hidden" name="id" id="<?php echo $page->id; ?>">
    <div>
        <label for="title">Page Name</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($page->title, ENT_QUOTES);?>" required>

        <label for="slug">Page Slug</label>
        <textarea name="slug" id="slug"><?php echo htmlspecialchars($page->slug, ENT_QUOTES);?></textarea>

        <label for="content">Page Content</label>
        <?php if (!empty($page->content)): ?>
            <input type="hidden" name="content" value="<?php echo $page->content; ?>">
        <?php endif; ?>
        <textarea cols="30" rows="30" name="content" id="content"></textarea>
    </div>

    <button type="submit">Update Page</button>
</form>
<?php
