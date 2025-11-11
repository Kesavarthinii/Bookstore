<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$table = $_GET['table'] ?? 'books';
$search = $_GET['search'] ?? '';
$genre_filter = $_GET['genre'] ?? '';

// Validate table
if (!in_array($table, ['books', 'new_arrivals'])) {
    die("Invalid table selected.");
}

// Prepare SQL query with optional filters
$sql = "SELECT * FROM $table WHERE 1";
$params = [];
$types = "";

if ($search) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}
if ($genre_filter) {
    $sql .= " AND genre=?";
    $params[] = $genre_filter;
    $types .= "s";
}

$sql .= " ORDER BY uploaded_at DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Books - Admin</title>
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; padding:20px; }
.container { max-width:1000px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2); }
h2 { text-align:center; margin-bottom:20px; }
form { display:flex; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
input, select, button { padding:8px; border-radius:5px; border:1px solid #ccc; }
button { background:#2e3d33; color:#fff; border:none; cursor:pointer; }
button:hover { background:#1f2a22; }
table { width:100%; border-collapse:collapse; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:left; vertical-align:middle; }
th { background:#2e3d33; color:#fff; text-transform:uppercase; }
img { width:50px; height:70px; object-fit:cover; border-radius:4px; }
a.button { padding:5px 10px; background:#2e3d33; color:#fff; border-radius:4px; text-decoration:none; margin-right:5px; }
a.button:hover { background:#1f2a22; }
.delete-btn { background:red !important; }
</style>
</head>
<body>

<div class="container">
    <h2>Update Books / New Arrivals</h2>

    <form method="get">
        <label>Table:
            <select name="table">
                <option value="books" <?= $table=='books' ? 'selected':'' ?>>Books</option>
                <option value="new_arrivals" <?= $table=='new_arrivals' ? 'selected':'' ?>>New Arrivals</option>
            </select>
        </label>
        <label>Search Title:
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
        </label>
        <label>Genre:
            <select name="genre">
                <option value="">All</option>
                <?php 
                $genres = ['Biography','Mystery','Romance','Thriller','History','Fantasy','Horror'];
                foreach($genres as $g): ?>
                    <option value="<?= $g ?>" <?= $genre_filter==$g?'selected':'' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Filter</button>
    </form>

    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($row['cover_image']) ?>" alt="cover"></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['author']) ?></td>
                <td><?= htmlspecialchars($row['genre']) ?></td>
                <td>
                    <a class="button" href="edit_book.php?table=<?= $table ?>&id=<?= $row['id'] ?>">Edit</a>
                    <a class="button delete-btn" href="delete_book.php?table=<?= $table ?>&id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                </td>
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
