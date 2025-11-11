<?php 
session_start();
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = trim($_POST['admin_id']);
    $password = $_POST['password'];

    // Check admin table
    $stmt = $conn->prepare("SELECT * FROM admins WHERE id=?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header("Location: admin_books.php"); // ✅ Admin dashboard
            exit();
        } else {
            $error = "❌ Password is incorrect!";
        }
    } else {
        $error = "❌ Admin ID not found!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - Bookit</title>
<style>
body { 
    margin:0; padding:0; font-family:Arial,sans-serif; 
    display:flex; justify-content:center; align-items:center; height:100vh; background:#f4f4f4;
}
.header {
    position:absolute; top:20px; left:20px; display:flex; align-items:center;
}
.header img {
    width:40px; height:40px; margin-right:10px;
}
.header h1 {
    font-family:'Playfair Display', serif; font-size:24px; margin:0; color:#2e3d33;
}
.login-card {
    background:#fff; padding:40px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2); width:350px;
}
.login-card h2 { text-align:center; margin-bottom:20px; font-family:'Playfair Display', serif; }
.login-card input { width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc; }
.login-card button { width:100%; padding:10px; background:#2e3d33; color:#fff; border:none; cursor:pointer; border-radius:5px; }
.login-card button:hover { background:#1f2a22; }
.error { color:red; text-align:center; margin-bottom:10px; }
</style>
</head>
<body>

<!-- App name + logo -->
<div class="header">
    <img src="assets/open-book.png" alt="Bookit Logo">
    <h1>Bookit</h1>
</div>

<div class="login-card">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <input type="number" name="admin_id" placeholder="Admin ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
