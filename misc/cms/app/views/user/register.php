<form action="/register" method="POST">
  <label for="username">Username</label>
  <input type="text" name="username" id="username" required>

  <label for="email">Email</label>
  <input type="email" name="email" id="email" required>

  <label for="password">Password</label>
  <input type="password" name="password" id="password" required>

  <input type="text" name="role" id="role">

  <button type="submit">Register User</button>
</form>

<?php if (isset($result)): ?>
  <div class="<?= $result['status'] ?>">
    <?= htmlspecialchars($result['message']) ?>
  </div>
<?php endif; ?>
