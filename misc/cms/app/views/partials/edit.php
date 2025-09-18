<form method="POST" action="/partials/update/header">
    <label for="content">Edit Partial: <?php echo htmlspecialchars($name); ?></label>
    <textarea name="content" rows="15" cols="100"><?= htmlspecialchars($content) ?></textarea>
    <button type="submit">Save</button>
</form>
