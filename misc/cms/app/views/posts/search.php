<?php
// Assuming you get posts from DB or elsewhere
// Example: $posts = searchPosts($_GET['query']);
$posts = isset($posts) ? $posts : []; // fallback if not passed in
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            margin-bottom: 30px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
        }

        button {
            padding: 10px 20px;
            border: none;
            background: #007BFF;
            color: white;
            cursor: pointer;
            border-radius: 0 4px 4px 0;
        }

        button:hover {
            background: #0056b3;
        }

        .post {
            margin-bottom: 25px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #007BFF;
            border-radius: 4px;
        }

        .post h2 {
            margin: 0 0 10px;
        }

        .post p {
            margin: 0;
        }

        .no-results {
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="GET" action="/search">
        <input type="text" name="query" value="<?= htmlspecialchars($query) ?>" placeholder="Search posts...">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars(isset($post->title) ? $post->title : 'Untitled') ?></h2>
                <p><?= !empty($post->content) ? htmlspecialchars($post->content) : '<em>No content available.</em>' ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results">No posts found.</p>
    <?php endif; ?>
</div>
</body>
</html>
