<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Bookit</title>
<style>
body {
    margin:0; padding:0; font-family:Arial, sans-serif;
    background:#f4f4f4;
}
.header {
    background:#2e3d33; color:#fff; padding:15px; text-align:center;
}
.container {
    display:flex; justify-content:space-around; flex-wrap:wrap;
    margin:30px;
}
.card {
    background:#fff; width:300px; height:180px;
    border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2);
    display:flex; justify-content:center; align-items:center;
    margin:15px; text-align:center; cursor:pointer; transition:.3s;
    flex-direction: column;
}
.card:hover {
    transform:scale(1.05);
    background:#f0f0f0;
}
.card a, .card select, .card button {
    text-decoration:none; color:#2e3d33; font-size:18px; font-weight:bold;
    margin:5px 0;
}
.card select, .card button {
    padding:8px 10px;
    border-radius:5px;
    border:1px solid #ccc;
    cursor:pointer;
}
.logout {
    text-align:right; margin:10px 20px;
}
.logout a {
    color:red; text-decoration:none; font-weight:bold;
}
</style>
</head>
<body>

<div class="header">
    <h1>Welcome, Admin</h1>
</div>
<div class="logout">
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <div class="card">
        <a href="add_arrivals.php">➕ Add New Arrivals</a>
    </div>
    <div class="card">
        <a href="add_books.php">📚 Upload Books</a>
    </div>
    <div class="card">
        <a href="view_ratings_admin.php">⭐ View Reviews</a>
    </div>

    <!-- New Card: Update Books -->
    <div class="card">
        <strong>✏️ Update Books</strong>
        <form action="update_books.php" method="get">
            <select name="table" required>
                <option value="">-- Select Table --</option>
                <option value="books">Books</option>
                <option value="new_arrivals">New Arrivals</option>
            </select>
            <button type="submit">Go</button>
        </form>
    </div>

    <!-- New Card: Show Books -->
    <div class="card">
        <a href="show_books.php">📖 Show All Books</a>
    </div>
</div>

</body>
</html>
