<div>
    <div style="text-align: right;">
        <a href="/categories">View Categories</a>
        <!--<a href="/admin/categories/create">Create Categories</a>-->

    </div>

    <h1>Create Category</h1>
    <form action="/categories/store" method="POST">
        <div style="margin-bottom: 20px;">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div>
            <button type="submit">Create Category</button>
        </div>
    </form>
</div>
<?php
