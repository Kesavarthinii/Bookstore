<?php
session_start();
include 'config.php';
// optional admin header

$sql = "SELECT 
            r.rating, 
            r.comment, 
            r.created_at,
            u.username, 
            b.title, 
            b.cover_image
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN books b ON r.book_id = b.id
        ORDER BY b.title ASC, r.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - View All Ratings</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f6f8;
        margin: 0;
        padding: 20px;
    }
    h1 {
        text-align: center;
        color: #2e3d33;
        margin-bottom: 25px;
    }
    .table-container {
        max-width: 1000px;
        margin: auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        vertical-align: top;
    }
    th {
        background: #2e3d33;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    tr:hover {
        background: #f1f1f1;
    }
    img {
        width: 50px;
        height: 70px;
        border-radius: 4px;
        object-fit: cover;
        margin-right: 8px;
    }
    .book-info {
        display: flex;
        align-items: center;
    }
    .rating {
        color: #f1c40f;
        font-weight: bold;
    }
    .comment {
        color: #444;
        font-size: 14px;
        line-height: 1.4;
    }
    .no-data {
        text-align: center;
        color: #888;
        padding: 20px;
    }
</style>
</head>
<body>

<h1>📚 All Book Ratings & Reviews</h1>

<div class="table-container">
    <?php if (mysqli_num_rows($result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Book</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td>
                    <div class="book-info">
                        <img src="<?= htmlspecialchars($row['cover_image']) ?>" alt="Cover">
                        <div><strong><?= htmlspecialchars($row['title']) ?></strong></div>
                    </div>
                </td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td class="rating"><?= $row['rating'] ?> ★</td>
                <td class="comment"><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
                <td><small><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></small></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="no-data">No ratings or reviews found.</div>
    <?php endif; ?>
</div>

</body>
</html>
