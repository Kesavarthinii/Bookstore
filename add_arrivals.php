<?php
session_start();
include 'config.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];

    // Create folder paths dynamically
    $cover_dir = "assets/cover-image/";
    $pdf_dir = "assets/" . strtolower($genre) . "/"; // e.g., assets/biography/

    // Ensure folders exist
    if (!is_dir($cover_dir)) mkdir($cover_dir, 0777, true);
    if (!is_dir($pdf_dir)) mkdir($pdf_dir, 0777, true);

    // File upload handling
    $cover_image = "";
    $pdf_file = "";

    if (!empty($_FILES['cover_image']['name'])) {
        $cover_image = $cover_dir . basename($_FILES['cover_image']['name']);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_image);
    }

    if (!empty($_FILES['pdf_file']['name'])) {
        $pdf_file = $pdf_dir . basename($_FILES['pdf_file']['name']);
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdf_file);
    }

    // Insert into new_arrivals table
    $stmt = $conn->prepare("INSERT INTO new_arrivals (title, author, genre, description, cover_image, pdf_file) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $author, $genre, $description, $cover_image, $pdf_file);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ New arrival added successfully!</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add New Arrival - Bookit</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px; }
.container { max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2); }
input, textarea, select { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
button { padding:10px 20px; background:#2e3d33; color:#fff; border:none; border-radius:5px; cursor:pointer; }
button:hover { background:#1f2a22; }
label { font-weight: bold; display: block; margin-top: 10px; }
</style>
</head>
<body>
<div class="container">
    <h2>Add New Arrival</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>

        <!-- ✅ Genre Dropdown -->
        <label for="genre">Select Genre:</label>
        <select name="genre" id="genre" required>
            <option value="">-- Select Genre --</option>
            <option value="Biography">Biography</option>
            <option value="Mystery">Mystery</option>
            <option value="Romance">Romance</option>
            <option value="Thriller">Thriller</option>
            <option value="History">History</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Horror">Horror</option>
        </select>

        <textarea name="description" placeholder="Description" rows="5"></textarea>

        <label>Upload Cover Image:</label>
        <input type="file" name="cover_image" accept="image/*" required>

        <label>Upload PDF File:</label>
        <input type="file" name="pdf_file" accept="application/pdf" required>

        <button type="submit">Add New Arrival</button>
    </form>
</div>
</body>
</html>
