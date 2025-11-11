<?php
session_start();
include 'config.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); // replaced email with username
    $password = $_POST['password'];

    // Check if username exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];        // optional
            $_SESSION['username'] = $row['username']; // store username in session
            $_SESSION['email'] = $row['email'];       // keep email in session
            header("Location: home.php");
            exit();
        } else {
            $error = "Password is incorrect!";
        }
    } else {
        $error = "No account found with this username!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Online Bookstore</title>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
<style>
/* --- Reusing Signup Page Styles --- */
body {
    margin: 0;
    padding: 0;
    font-family: 'Merriweather', serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url('assets/background.png') no-repeat center center fixed;
    background-size: cover;
}
.login-card-wrapper {
    background-color: #fcfaf6;
    border-radius: 20px;
    width: 420px;
    max-width: 90%;
    padding: 50px 20px 70px; /* extra space for open book */
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    position: relative;
}
.card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5em;
    color: #333;
    font-weight: bold;
    margin-bottom: 25px;
    text-align: left;
    line-height: 1.3;
}
.login-form-section {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.login-form-section input {
    width: 90%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: 1px solid #d8d2c4;
    border-radius: 8px;
    font-size: 1em;
    background-color: #fff;
    color: #333;
    outline: none;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
}
.login-form-section button {
    width: 90%;
    padding: 12px;
    border-radius: 8px;
    border: none;
    background-color: #2e3d33;
    color: #fff;
    font-family: 'Playfair Display', serif;
    font-weight: bold;
    font-size: 1em;
    cursor: pointer;
    transition: background 0.3s ease;
}
.login-form-section button:hover { background-color: #1f2a22; }
.form-links {
    margin-top: 15px;
    font-size: 0.85em;
    color: #555;
    text-align: center;
}
.form-links a { color: #2e3d33; text-decoration: none; }
.form-links a:hover { text-decoration: underline; }
.error { text-align:center; color: red; margin-bottom: 10px; }

/* --- Open Book Illustration --- */
.open-book-illustration {
    width: 100%;                  
    height: 80px;                
    background: url('assets/open-book.png') no-repeat center bottom;
    background-size: contain;    
    margin: 20px auto 0;         
    display: block;
}
</style>
</head>
<body>
    <div class="login-card-wrapper">
    <div class="card-title">
        <span>Hey buddy!</span>
        <span>Your personal library awaits</span>
    </div>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form class="login-form-section" action="" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="form-links">
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>

    <div class="open-book-illustration"></div>
</div>

</body>
</html>
