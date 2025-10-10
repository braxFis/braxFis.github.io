<html>
<body>

<div class="container">

  <div class="notification">
    <?php echo $notification->title;?>
    <?php echo $notification->date;?>
  </div>

  <div class="zone" data-controller="Dashboard">
    <h3>Dashboard</h3>
    <div class="dropzone">
      <?php foreach($dashboardModules as $m):?>
      <div class="module" draggable="true" data-id="<?php echo $m->id;?>">
        <?= htmlspecialchars($m->name);?>
      </div>
      <?php endforeach;?>
    </div>
  </div>

  <div class="zone" data-controller="Footer">
    <h3>Footer</h3>
    <div class="dropzone">
      <?php foreach ($footerModules as $m): ?>
        <div class="module" draggable="true" data-id="<?= $m->id ?>">
          <?= htmlspecialchars($m->name) ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="reviews">
    <h2>Reviews</h2>
    <ul>
      <?php foreach($reviews as $review):?>
      <?php if($review != null):?>
        <li><a href="<?php echo 'review/indie/' . $review->id;?>"><?php echo $review->title;?></a></li>
        <?php else:?>
        <?php echo "No reviews found";?>
      <?php endif; ?>
      <?php endforeach;?>
    </ul>
  </div>

  <div class="previews">
    <h2>Previews</h2>
    <ul>
      <?php foreach($previews as $preview):?>
        <li><a href="<?php echo 'preview/indie/' . $preview->id;?>"><?php echo $preview->title;?></a></li>
      <?php endforeach;?>
    </ul>
  </div>

  <div class="news">
    <h2>News</h2>
    <ul>
      <?php foreach($news as $new):?>
        <li><a href="<?php echo 'news/indie/' . $new->id;?>"><?php echo $new->title;?></a></li>
      <?php endforeach;?>
    </ul>
  </div>
</div>

<div class="charts">
  <h2>Charts</h2>
  <?php require __DIR__ . '/../../views/statistics/index.php';?>
</div>
<script>
  let dragged = null;

  document.querySelectorAll('.module').forEach(el => {
    el.addEventListener('dragstart', e => {
      dragged = e.target;
      e.target.classList.add('dragging');
    });
    el.addEventListener('dragend', e => {
      e.target.classList.remove('dragging');
      dragged = null;
    });
  });

  document.querySelectorAll('.dropzone').forEach(zone => {
    zone.addEventListener('dragover', e => e.preventDefault());
    zone.addEventListener('drop', e => {
      e.preventDefault();
      if (!dragged) return;
      zone.appendChild(dragged);

      const toController = zone.closest('.zone').dataset.controller;
      const id = dragged.dataset.id;

      fetch('/modules/move', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id, to: toController, position: 1 })
      })
        .then(r => r.json())
        .then(console.log);
    });
  });
</script>
</body>
</html>
