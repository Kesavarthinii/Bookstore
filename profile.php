<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
$username=$_SESSION['username'];
$email=$_SESSION['email']??'N/A';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile</title>
<style>
body { margin:0; padding:0; font-family:'Merriweather',serif; background:#fcfaf6; }
.profile-box { background:#fff; max-width:400px; margin:40px auto; padding:30px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); text-align:center; }
.profile-box h2 { margin-bottom:20px; }
button { background:#2e3d33; color:#fff; padding:10px 20px; border:none; border-radius:6px; cursor:pointer; }
button:hover { background:#1f2a22; }
</style>
</head>
<body>
<div class="profile-box">
    <h2>Your Profile</h2>
    <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    <a href="logout.php"><button>Logout</button></a>
</div>
</body>
</html>
