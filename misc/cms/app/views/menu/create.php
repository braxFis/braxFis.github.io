<div>
    <div>
        <a href="/"></a>
    </div>

    <h1>Create Menu</h1>

    <form action="/menu/store" method="POST">
        <div>
            <label for="name">Menu Item Name</label>
            <input type="text" name="label" id="label" required>

            <label for="name">Menu Item Url</label>
            <input type="text" name="url" id="url" required>

            <label for="name">Menu Item Sort Order</label>
            <input type="text" name="sort_order" id="sort_order" required>

        </div>

        <button type="submit">Create Menu Item</button>
    </form>
</div>
