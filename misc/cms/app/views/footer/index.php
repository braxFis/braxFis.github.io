<h2>Footer Edit</h2>
<footer>
<div class="footer-container">
<ul id="footer-list">
<?php foreach ($footers as $footer): ?>
<div class="footer-column">
    <h1>TBA</h1>
        <li draggable="true" data-id="<?php echo $footer->id;?>">
                <a href="<?php echo $footer->url;?>">
                    <?php echo $footer->label;?>
                </a>
        </li>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
            <li>
                <a href="/footer/edit/<?php echo $footer->id;?>">Edit</a>
                <!-- Delete Footer -->
                <form action="/footer/delete/<?php echo $footer->id;?>" method="POST">
                    <input type="hidden" name="id" id="" value="<?php echo $footer->id;?>">
                    <button type="submit">Delete Footer</button>
                </form>
            </li>
        <?php endif;?>
</div>
<?php endforeach; ?>
</ul>

<ul>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
        <a href="/footer/create/">Create Footer</a>
    <?php endif; ?>
</ul>
</div>
</footer>
<script>
  const list = document.getElementById('footer-list');
  let draggingEl;

  list.addEventListener('dragstart', e => {
    draggingEl = e.target;
    e.dataTransfer.effectAllowed = 'move';
  });
  list.addEventListener('dragover', e => {
    e.preventDefault();
    const target = e.target.closest('li');
    if (target && target !== draggingEl) {
      const rect = target.getBoundingClientRect();
      const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
      list.insertBefore(draggingEl, next ? target.nextSibling : target);
    }
  });
  list.addEventListener('drop', () => {
    const order = {};
    document.querySelectorAll('#footer-list li').forEach((li, index) => {
      order[li.dataset.id] = index + 1;
    });

    fetch('/footer/update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ order })
    })
      .then(res => res.json())
      .then(data => console.log(data));
  });
</script>
