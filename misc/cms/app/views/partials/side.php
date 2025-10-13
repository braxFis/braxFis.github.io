<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
  new Sortable(document.getElementById('sortable-list'), {
    animation: 150,
    onEnd: function (evt) {
      let order = [];
      document.querySelectorAll('#sortable-list .sortable-item').forEach((el, index) => {
        order.push({ id: el.dataset.id, position: index+1 });
      });

      fetch('/item/index', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(order)
      });
    }
  });
</script>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){ ?>}
<div class="navigation-rail">
    <div class="menu-fab">
        <div class="menu">
            <div class="container">
                <div class="state-layer nav-item">
                    <a href="/media" style="color:white;">
                      <p>Media</p>
                      <ul id="sortable-list">
                        <?php foreach($items as $item): ?>
                          <li class="sortable-item" data-id="<?php echo $item->id; ?>">
                            <?php echo $item->title; ?>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-fab">
        <div class="menu">
            <div class="container">
                <div class="state-layer nav-item">
                    <a href="#/content">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
