<h1>Edit Media</h1>

<form action="/media/update/<?= htmlspecialchars($media->id) ?>" method="POST">
    <label for="title">Media Title</label>
    <input type="text" name="title" id="title" required
           value="<?= isset($media->title) ? htmlspecialchars($media->title) : '' ?>">

    <label for="media_type">Media Type</label>
    <select name="media_type" id="media_type" required>
        <?php foreach ($mediaTypes as $type): ?>
            <option value="<?= htmlspecialchars($type) ?>"
                <?= (isset($media->media_type) && $media->media_type === $type) ? 'selected' : '' ?>>
                <?= ucfirst($type) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="url">Media URL</label>
    <input type="url" name="url" id="url" required
           value="<?= isset($media->url) ? htmlspecialchars($media->url) : '' ?>">

    <label for="related_type">Category</label>
    <select name="related_type" id="related_type" required>
        <?php foreach ($mediaCategories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>"
                <?= (isset($media->related_type) && $media->related_type === $cat) ? 'selected' : '' ?>>
                <?= ucfirst($cat) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="related_id">Related ID</label>
    <input type="number" name="related_id" id="related_id" required
           value="<?= isset($media->related_id) ? htmlspecialchars($media->related_id) : '' ?>">

    <label for="uploaded_by">Uploaded By</label>
    <input type="text" name="uploaded_by" id="uploaded_by" required
           value="<?= isset($media->uploaded_by) ? htmlspecialchars($media->uploaded_by) : '' ?>">

    <label for="uploaded_at">Uploaded At</label>
    <input type="text" name="uploaded_at" id="uploaded_at"
           value="<?= isset($media->uploaded_at) ? htmlspecialchars($media->uploaded_at) : '' ?>">

    <input type="file" name="image_url" required>

    <button type="submit">Update Media</button>
</form>
