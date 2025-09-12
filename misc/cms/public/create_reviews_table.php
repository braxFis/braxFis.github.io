<?php

require __DIR__ . '/../bootstrap.php';

$db = new Database();

$query = "CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT NOT NULL,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  author VARCHAR(255) NOT NULL,
  category ENUM(''),
  genre ENUM('action', 'adventure', 'rpg', 'strategy', 'simulator', 'sport', 'racing', 'fps', 'puzzle', 'platformer', 'survival', 'edu'),
  media VARCHAR(255) NOT NULL,
  platform ENUM ('PC', 'PS4', 'PS5', 'XBOX ONE', 'Nintendo Switch', 'Mobile', 'VR'),
  status ENUM('published', 'draft') NOT NULL,
  tags ENUM('Multiplayer', 'Indie', 'Free-to-play', 'Early Access'),
  PRIMARY KEY(id)
)";

$db->conn->exec($query);
echo "Reviews Table Created";
