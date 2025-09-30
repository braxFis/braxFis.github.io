<div>
  <div>
    <a href="/"></a>
  </div>

  <h1>Install Plugin</h1>
  <form action="/plugins/store" method="POST" enctype="multipart/form-data">
    <div>
      <label for="name">Plugin Title</label>
      <input type="text" name="name" id="name" required>

      <label for="active">Plugin Status</label>
      <input type="number" name="active" id="active" required>
    </div>

    <label for="zip">Plugin ZIP</label>
    <input type="file" name="zip" id="zip" accept="application/zip" required>
    <button type="submit">Install Plugin</button>
  </form>
</div>
