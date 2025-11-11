<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["rating" => null, "comment" => ""]);
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['book_id']);

$sql = "SELECT rating, comment FROM reviews WHERE user_id=? AND book_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(["rating" => null, "comment" => ""]);
}
