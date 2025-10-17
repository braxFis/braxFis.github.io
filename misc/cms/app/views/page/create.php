<h1>Create Page</h1>

<form id="pageForm" action="/page/store" method="POST">
  <label for="title">Title</label>
  <input type="text" name="title" id="title" required>

  <label for="slug">Slug</label>
  <input type="text" name="slug" id="slug">
  
  <input type="hidden" name="layout" id="layoutInput">

  <button type="submit">Create Page</button>
</form>

<?php 
$page = $this->model->getBySlug($slug); // eller getBySlug($slug)
$slug = $page['slug'];
?>
<?= (new \app\controllers\DragDropController())->editor($slug); ?>