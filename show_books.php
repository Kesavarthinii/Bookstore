<?php
session_start();
include 'config.php';

// Handle search query
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

// Fetch all books and new arrivals together
$sql = "
    SELECT id, title, author, genre, cover_image, pdf_file, 'books' AS source FROM books
    UNION ALL
    SELECT id, title, author, genre, cover_image, pdf_file, 'new_arrivals' AS source FROM new_arrivals
";

// Apply search filter if needed
if ($search) {
    $sql = "
        SELECT * FROM (
            $sql
        ) AS all_books
        WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR genre LIKE '%$search%'
    ";
}

$sql .= " ORDER BY title ASC";

$result = $conn->query($sql);
if (!$result) die("SQL Error: " . $conn->error);

// Fetch genre-wise count
$genre_sql = "
    SELECT genre, COUNT(*) AS total FROM (
        SELECT genre FROM books
        UNION ALL
        SELECT genre FROM new_arrivals
    ) AS all_books
    GROUP BY genre
";
$genre_result = $conn->query($genre_sql);

// Fetch total books count
$total_sql = "
    SELECT COUNT(*) AS total_books FROM (
        SELECT id FROM books
        UNION ALL
        SELECT id FROM new_arrivals
    ) AS all_books
";
$total_result = $conn->query($total_sql);
$total_books = $total_result->fetch_assoc()['total_books'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Books - Admin</title>
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; padding:20px; }
.container { max-width:1200px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2); }
h2 { margin-top:0; }
.search-bar { margin-bottom:15px; }
.search-bar input[type="text"] { width:300px; padding:8px; border-radius:5px; border:1px solid #ccc; }
.search-bar button { padding:8px 15px; border:none; background:#2e3d33; color:#fff; border-radius:5px; cursor:pointer; }
.search-bar button:hover { background:#1f2a22; }
.summary { display:flex; gap:20px; margin-bottom:20px; flex-wrap:wrap; }
.summary .card { background:#f8f8f8; padding:15px; border-radius:6px; text-align:center; box-shadow:0 3px 8px rgba(0,0,0,0.1); width:150px; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:left; }
th { background:#2e3d33; color:#fff; }
img { width:50px; height:70px; object-fit:cover; border-radius:4px; }
</style>
</head>
<body>

<div class="container">
    <h2>All Books & New Arrivals</h2>

    <!-- Search Bar -->
    <form class="search-bar" method="get">
        <input type="text" name="search" placeholder="Search by title, author, genre..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Summary Cards -->
    <div class="summary">
        <div class="card">
            <strong>Total Books</strong><br>
            <?= $total_books ?>
        </div>

        <?php if ($genre_result->num_rows > 0): ?>
            <?php while($g = $genre_result->fetch_assoc()): ?>
                <div class="card">
                    <strong><?= htmlspecialchars($g['genre']) ?></strong><br>
                    <?= $g['total'] ?> books
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- All Books Table -->
    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Source</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($row['cover_image']) ?>" alt="cover"></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['author']) ?></td>
                <td><?= htmlspecialchars($row['genre']) ?></td>
                <td><?= htmlspecialchars(ucfirst($row['source'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>

</div>

</body>
</html>
