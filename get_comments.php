<?php
session_start();
include 'config.php';

$book_id = intval($_GET['book_id']);

// Get average rating
$avg_sql = "SELECT AVG(rating) as avg_rating FROM reviews WHERE book_id=?";
$stmt = $conn->prepare($avg_sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$avg_result = $stmt->get_result()->fetch_assoc();
$avg_rating = $avg_result['avg_rating'] ? round($avg_result['avg_rating'], 1) : "No ratings yet";

// Get all comments with usernames
$sql = "SELECT r.comment, r.rating, u.username 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.book_id=? 
        ORDER BY r.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Average Rating: $avg_rating ★</h3>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['username']) . "</strong> ";
        if ($row['rating']) {
            echo "rated <span style='color:gold'>★ {$row['rating']}</span><br>";
        }
        if ($row['comment']) {
            echo htmlspecialchars($row['comment']);
        }
        echo "</p>";
    }
} else {
    echo "<p>No reviews yet. Be the first to comment!</p>";
}
