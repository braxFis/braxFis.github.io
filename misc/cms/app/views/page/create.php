<div>
    <div>
        <a href="/"></a>
    </div>

    <h1>Create Page</h1>

    <form action="/page/store" method="POST" enctype="multipart/form-data">
        <div>
            <label for="title">Page Title</label>
            <input type="text" name="title" id="title" required>

            <label for="slug">Page Slug</label>
            <input type="text" id="slug" name="slug">

            <label for="content">Page Content</label>
            <textarea cols="30" rows="30" name="content" id="content"><?= isset($page) ? htmlspecialchars($page->content) : '' ?></textarea>

        </div>

        <button type="submit">Create Page</button>
    </form>
</div>
