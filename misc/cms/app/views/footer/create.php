<div>
    <div>
        <a href="/"></a>
    </div>

    <h1>Create Footer</h1>

    <form action="/footer/store" method="POST">
        <div>
            <label for="name">Footer Item Name</label>
            <input type="text" name="label" id="label" required>

            <label for="name">Footer Item Url</label>
            <input type="text" name="url" id="url" required>

            <label for="name">Footer Item Sort Order</label>
            <input type="text" name="sort_order" id="sort_order" required>

        </div>

        <button type="submit">Create Footer Item</button>
    </form>
</div>
