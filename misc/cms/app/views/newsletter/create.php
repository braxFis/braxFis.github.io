<div>
    <div>
        <a href="/">Back Home</a>
    </div>

    <h1>Create Newsletter</h1>

    <form action="/newsletter/store" method="POST">
        <div>
            <label for="title">Newsletter Name</label>
            <input type="text" name="title" id="title" required>

            <label for="body">Newsletter Body</label>
            <textarea name="body" id="body" cols="30" rows="10"></textarea>

            <label for="created_at">Created At</label>
            <input type="date" name="created_at" id="date">

            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="draft">Draft</option>
                <option value="sent">Sent</option>
            </select>

            <button type="submit">Submit</button>
        </div>
    </form>
</div>