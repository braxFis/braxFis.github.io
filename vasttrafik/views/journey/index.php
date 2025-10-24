<?php include __DIR__ . '/../layouts/main.php'; ?>

<div class="search-container">
    <form id="journeyForm">
        <label for="origin">Origin</label>
        <input type="text" id="origin" name="origin" required>

        <label for="destination">Destination</label>
        <input type="text" id="destination" name="destination" required>

        <button type="submit">Sök Resa</button>
    </form>
</div>

<div id="results"></div>

<script src="/vasttrafik/public/js/vasttrafik.js"></script>
