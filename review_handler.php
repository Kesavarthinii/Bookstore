<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in!";
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);
$rating = intval($_POST['rating']);
$comment = trim($_POST['comment']);

// Check if review exists
$sql = "SELECT * FROM reviews WHERE user_id=? AND book_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing review
    $sql = "UPDATE reviews SET rating=?, comment=? WHERE user_id=? AND book_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $rating, $comment, $user_id, $book_id);
    $stmt->execute();
    echo "Review updated!";
} else {
    // Insert new review
    $sql = "INSERT INTO reviews (user_id, book_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $user_id, $book_id, $rating, $comment);
    $stmt->execute();
    echo "Review submitted!";
}
