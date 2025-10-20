<form action="/profile/update" method="POST" enctype="multipart/form-data">
  <label for="image">Image</label>
  <input type="file" name="image" required>
  <?php if (!empty($profile->image)): ?>
    <img src="<?= htmlspecialchars($profile->image) ?>" alt="Profile Image" style="max-width: 150px;">
  <?php endif; ?>
  <label for="username">Username</label>
  <input type="text" name="username" id="username" value="<?= htmlspecialchars($profile->username) ?>">

  <label for="email">Email</label>
  <input type="email" name="email" id="email" value="<?= htmlspecialchars($profile->email) ?>">

  <label for="password">New Password (leave blank to keep current)</label>
  <input type="password" name="password" id="password">

  <label for="created_at">Created Date</label>
  <input type="text" name="created_at" id="created_at" value="<?= htmlspecialchars($profile->created_at) ?>" readonly>

  <label for="role">Role</label>
  <input type="text" name="role" id="role" value="<?= htmlspecialchars($profile->role) ?>">

  <!-- Support for Subscriptions -->
  <label for="subscribe">Subscribe</label>
  <input type="checkbox" name="subscribe" value="<?= htmlspecialchars($profile->subscribe) ?>)">

  <button type="submit">Update Profile</button>

  <a href="/profile/confirm-delete">Delete Profile</a>

  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="/logout" style="position: absolute; top: 10px; right: 10px;">
      <button type="button">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</button>
    </a>
  <?php endif; ?>
</form>
