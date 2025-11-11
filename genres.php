<?php
include 'config.php';
include 'header.php';

$genres = [
    'biography' => 'Biography',
    'fantasy' => 'Fantasy',
    'history' => 'History',
    'horror' => 'Horror',
    'mystery' => 'Mystery',
    'romance' => 'Romance',
    'self-help' => 'Self-Help',
    'thriller' => 'Thriller'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Browse by Genre</title>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Merriweather', serif;
        background-color: #fcfaf6;
    }

    .main-container {
        padding: 1px 40px 50px;
    }

    h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.3em;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
    }

    .genre-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
    }

    .genre-item {
        text-align: center;
        width: 180px;
    }

    .genre-item a {
        text-decoration: none;
        color: #333;
    }

    .genre-image {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .genre-item a:hover .genre-image {
        transform: scale(1.07);
        box-shadow: 0 6px 16px rgba(0,0,0,0.25);
    }

    .genre-name {
        margin-top: 12px;
        font-size: 1.1em;
        font-weight: bold;
        color: #444;
        transition: color 0.3s ease;
    }

    .genre-item a:hover .genre-name {
        color: #2e3d33;
    }
</style>
</head>
<body>

<div class="main-container">
    <h1>Browse by Genre</h1>
    <ul class="genre-list">
        <?php foreach ($genres as $key => $name): ?>
            <li class="genre-item">
                <a href="<?= $key ?>.php">
                    <img src="assets/genres/<?= $key ?>.png" alt="<?= $name ?>" class="genre-image">
                    <div class="genre-name"><?= $name ?></div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>

