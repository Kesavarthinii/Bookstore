<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$table = $_GET['table'] ?? 'books';
$id = intval($_GET['id'] ?? 0);

if (!in_array($table, ['books', 'new_arrivals'])) {
    die("Invalid table selected.");
}

if ($id <= 0) {
    die("Invalid book ID.");
}

// Fetch existing book
$stmt = $conn->prepare("SELECT * FROM $table WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Book not found.");
}

$book = $result->fetch_assoc();
$stmt->close();

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];

    // File paths
    $cover_image = $book['cover_image'];
    $pdf_file = $book['pdf_file'];

    // Handle cover upload
    if (!empty($_FILES['cover_image']['name'])) {
        $cover_dir = "assets/cover-image/";
        if (!is_dir($cover_dir)) mkdir($cover_dir, 0777, true);
        $cover_image = $cover_dir . basename($_FILES['cover_image']['name']);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_image);
    }

    // Handle PDF upload
    if (!empty($_FILES['pdf_file']['name'])) {
        $pdf_dir = "assets/" . strtolower($genre) . "/";
        if (!is_dir($pdf_dir)) mkdir($pdf_dir, 0777, true);
        $pdf_file = $pdf_dir . basename($_FILES['pdf_file']['name']);
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdf_file);
    }

    // Update DB
    $stmt = $conn->prepare("UPDATE $table SET title=?, author=?, genre=?, description=?, cover_image=?, pdf_file=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $author, $genre, $description, $cover_image, $pdf_file, $id);

    if ($stmt->execute()) {
        $message = "✅ Book updated successfully!";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Book - Admin</title>
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; padding:20px; }
.container { max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2); }
input, textarea, select { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
button { padding:10px 20px; background:#2e3d33; color:#fff; border:none; border-radius:5px; cursor:pointer; }
button:hover { background:#1f2a22; }
label { font-weight:bold; display:block; margin-top:10px; }
img { width:100px; height:140px; object-fit:cover; border-radius:5px; margin-top:5px; }
.message { color:green; font-weight:bold; text-align:center; margin-bottom:10px; }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Book</h2>
    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

        <label>Author:</label>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>

        <label>Genre:</label>
        <select name="genre" required>
            <?php 
            $genres = ['Biography','Mystery','Romance','Thriller','History','Fantasy','Horror'];
            foreach($genres as $g): ?>
                <option value="<?= $g ?>" <?= $book['genre']==$g ? 'selected' : '' ?>><?= $g ?></option>
            <?php endforeach; ?>
        </select>

        <label>Description:</label>
        <textarea name="description" rows="5"><?= htmlspecialchars($book['description']) ?></textarea>

        <label>Cover Image:</label>
        <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="cover">
        <input type="file" name="cover_image" accept="image/*">

        <label>PDF File:</label>
        <a href="<?= htmlspecialchars($book['pdf_file']) ?>" target="_blank">View Current PDF</a>
        <input type="file" name="pdf_file" accept="application/pdf">

        <button type="submit">Update Book</button>
    </form>
</div>

</body>
</html>
