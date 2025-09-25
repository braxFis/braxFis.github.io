<div>
    <div>
        <a href="/"></a>
    </div>

    <h1>Create Page</h1>

    <form action="/page/store" method="POST">
        <div>
            <label for="title">Page Title</label>
            <input type="text" name="title" id="title" required>

            <label for="slug">Page Slug</label>
            <textarea name="slug" id="slug" cols="30" rows="10"></textarea>

            <label for="content">Content</label>
            <input type="text" name="content" id="content" required>

        </div>

        <button type="submit">Create Page</button>
    </form>
</div>
