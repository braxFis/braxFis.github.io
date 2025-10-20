<h1>Create Plan</h1>
<form action="/plan/store" method="POST">
  <div>
    <label for="name">Name</label>
    <input type="text" name="name" id="name">

    <label for="email">Email</label>
    <input type="email" name="email" id="email">

    <label for="phone">Phone</label>
    <input type="number" name="phone" id="phone">

    <label for="address">Address</label>
    <input type="text" name="address" id="address">

    <select name="type" id="type">
      <option value="free">Free</option>
      <option value="standard">Standard</option>
      <option value="premium">Premium</option>
    </select>

    <button type="submit">Create Plan</button>
  </div>
</form>
