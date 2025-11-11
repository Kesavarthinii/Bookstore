<?php
include 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $message = "Passwords do not match!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        if ($stmt->execute()) {
            $message = "Signup successful! <a href='book.php'>Login here</a>";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - Online Bookstore</title>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
<style>
/* --- your exact CSS (unchanged) --- */
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
.signup-card-wrapper {
    background-color: #fcfaf6;
    border-radius: 20px;
    width: 420px;
    max-width: 90%;
    padding: 50px 20px 50px;
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
.signup-form-section {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.signup-form-section input {
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
.signup-form-section button {
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
.signup-form-section button:hover { background-color: #1f2a22; }
.form-links {
    margin-top: 15px;
    font-size: 0.85em;
    color: #555;
    text-align: center;
}
.form-links a { color: #2e3d33; text-decoration: none; }
.form-links a:hover { text-decoration: underline; }
.message { text-align:center; color: green; margin-bottom: 10px; }
</style>
</head>
<body>
    <div class="signup-card-wrapper">
        <div class="card-title">
            <span>Join Bookit Today!</span>
            <span>Discover, read, and collect your favorite books.</span>
        </div>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <form class="signup-form-section" action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <div class="form-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
