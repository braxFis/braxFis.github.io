<!DOCTYPE html>
<html lang="sv">
<head>
<meta charset="UTF-8">
<title>Mina sparade artiklar</title>
<style>
body {
  font-family: system-ui, sans-serif;
  background: #f4f4f6;
  margin: 0;
  padding: 20px;
}

h1 {
  color: #333;
  text-align: center;
}

.read-list {
  max-width: 700px;
  margin: 0 auto;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  overflow: hidden;
}

.read-list table {
  width: 100%;
  border-collapse: collapse;
}

.read-list th, .read-list td {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
}

.read-list th {
  background: #fafafa;
  text-align: left;
  font-weight: 600;
}

.read-list tr:hover {
  background: #f9f9fb;
}

a.btn {
  text-decoration: none;
  color: white;
  background: #0078d7;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 0.9em;
}

a.btn.delete {
  background: #c0392b;
}
</style>
</head>
<body>

<h1>📘 Mina sparade artiklar</h1>
<!-- Läs Senare Widget -->
<?php

use app\widgets\ReadWidget;
echo ReadWidget::renderList($userId);
?>
<!-- End Läs Senare Widget -->
</body>
</html>
