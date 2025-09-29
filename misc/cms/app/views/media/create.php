<h1>Create Media</h1>

<form action="/media/store" method="POST" enctype="multipart/form-data">
    <label for="name">Media Title</label>
    <input type="text" name="title" id="title" required>

    <label for="media_type">Media Type</label>
    <select name="media_type" id="media_type" required>
        <?php foreach ($mediaTypes as $type): ?>
            <option value="<?= htmlspecialchars($type) ?>">
                <?= ucfirst($type) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="url">Media URL</label>
    <input type="url" name="url" id="url" disabled>

    <label for="related_type">Category</label>
    <select name="related_type" id="related_type" required>
        <?php foreach ($mediaCategories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>">
                <?= ucfirst($cat) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="related_id">Related ID</label>
    <input type="number" name="related_id" id="related_id" required>

    <label for="uploaded_by">Uploaded By</label>
    <input type="text" name="uploaded_by" id="uploaded_by" required value="<?= $_SESSION['username'] ?>">

    <label for="uploaded_at">Uploaded At</label>
    <input type="text" name="uploaded_at" id="uploaded_at" value="<?php echo date('Y-m-d H:i:s') ?>">

    <label for="image_url">Image Upload</label>
    <input type="file" name="image_url" id="image_url" accept="image/*" required>

    <button type="submit">Create Media</button>
</form>
