<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit;
}

if (!isset($_POST['book_id'])) {
    echo "Missing book id.";
    exit;
}

$book_id = intval($_POST['book_id']);

// If logged in -> toggle server-side bookmark
if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);

    // check if already bookmarked
    $check = $conn->prepare("SELECT id FROM bookmarks WHERE user_id=? AND book_id=?");
    $check->bind_param("ii", $user_id, $book_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        // remove
        $del = $conn->prepare("DELETE FROM bookmarks WHERE user_id=? AND book_id=?");
        $del->bind_param("ii", $user_id, $book_id);
        if ($del->execute()) {
            echo "Bookmark removed.";
        } else {
            echo "Could not remove bookmark.";
        }
    } else {
        // insert
        $ins = $conn->prepare("INSERT INTO bookmarks (user_id, book_id) VALUES (?, ?)");
        $ins->bind_param("ii", $user_id, $book_id);
        if ($ins->execute()) {
            echo "Book bookmarked!";
        } else {
            echo "Could not add bookmark.";
        }
    }
} else {
    // Guest: client-side handles bookmarking (localStorage). If client still calls this, return helpful message.
    echo "guest";
}
?>
