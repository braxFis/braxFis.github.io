<div class="controller-zone" data-controller="dashboard">
  <h2>Dashboard</h2>
  <div class="dropzone" ondrop="drop(event)" ondragover="allowDrop(event)">
    <?php foreach($dashboardComponents as $c): ?>
      <div class="component" draggable="true" ondragstart="drag(event)" data-id="<?= $c['id'] ?>">
        <?= htmlspecialchars($c['name']) ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="controller-zone" data-controller="footer">
  <h2>Footer</h2>
  <div class="dropzone" ondrop="drop(event)" ondragover="allowDrop(event)">
    <?php foreach($footerComponents as $c): ?>
      <div class="component" draggable="true" ondragstart="drag(event)" data-id="<?= $c['id'] ?>">
        <?= htmlspecialchars($c['name']) ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
