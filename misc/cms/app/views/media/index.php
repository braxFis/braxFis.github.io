<h2>Media Management</h2>
<link rel="stylesheet" href="/css/style.css">
<style>
    .tabs { margin-top: 20px; }
    .tab-links {
        display: flex;
        gap: 10px;
        list-style: none;
        padding: 0;
        border-bottom: 2px solid #ccc;
    }
    .tab-links li {
        padding: 8px 16px;
        background: #eee;
        cursor: pointer;
        border-radius: 5px 5px 0 0;
    }
    .tab-links li.active { background: #fff; font-weight: bold; }

    .tab-content .tab { display: none; padding: 20px; border: 1px solid #ccc; }
    .tab-content .tab.active { display: block; }

</style>
<div class="tabs">
    <ul class="tab-links">
        <?php foreach ($mediaCategories as $cat): ?>
            <li data-tab="<?= $cat ?>" class="<?= $cat === $mediaCategories[0] ? 'active' : '' ?>">
                <?= ucfirst($cat) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="tab-content">
        <?php foreach ($mediaCategories as $cat): ?>
            <div id="<?= $cat ?>" class="tab <?= $cat === $mediaCategories[0] ? 'active' : '' ?>">
                <?php foreach ($medias as $media): ?>
                    <?php if ($media->related_type === $cat): ?>
                        <div class="media-item">
                            <img src="<?= htmlspecialchars($media->url) ?>" alt="<?= htmlspecialchars($media->title) ?>" width="150">
                            <p><strong>Title:</strong> <?= htmlspecialchars($media->title) ?></p>

                            <label>Media Type</label>
                            <select name="media_type" required>
                                <?php foreach ($mediaTypes as $opt): ?>
                                    <option value="<?= $opt ?>" <?= $media->media_type === $opt ? 'selected' : '' ?>>
                                        <?= ucfirst($opt) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label>Category</label>
                            <select name="related_type" required>
                                <?php foreach ($mediaCategories as $optCat): ?>
                                    <option value="<?= $optCat ?>" <?= $media->related_type === $optCat ? 'selected' : '' ?>>
                                        <?= ucfirst($optCat) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <p><strong>URL:</strong> <a href="<?= htmlspecialchars($media->url) ?>" target="_blank"><?= htmlspecialchars($media->url) ?></a></p>

                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <div class="media-actions">
                                    <a href="/media/edit/<?= $media->id ?>">Edit</a>
                                    <form action="/media/delete/<?= $media->id ?>" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $media->id ?>">
                                        <button type="submit" onclick="return confirm('Delete this media item?')">Delete</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div class="create-media-link">
        <a href="/media/create">+ Create New Media</a>
    </div>
<?php endif; ?>
<script>
    document.querySelectorAll('.tab-links li').forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.tab;

            document.querySelectorAll('.tab-links li').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            document.querySelectorAll('.tab-content .tab').forEach(c => {
                c.classList.remove('active');
                if (c.id === target) c.classList.add('active');
            });
        });
    });
</script>
