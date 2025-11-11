<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo 'fail';
    exit;
}

if(isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = intval($_POST['book_id']);

    $stmt = $conn->prepare("DELETE FROM bookmarks WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("ii", $user_id, $book_id);
    if($stmt->execute()) {
        echo 'success';
    } else {
        echo 'fail';
    }
    $stmt->close();
    exit;
}

echo 'fail';
?>
