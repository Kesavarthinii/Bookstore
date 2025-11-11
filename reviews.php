<?php
include 'config.php';
include 'header.php';

$sql = "SELECT r.rating, r.comment, u.username, b.title, b.cover_image
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN books b ON r.book_id = b.id
        ORDER BY b.title, r.created_at DESC";

$result = mysqli_query($conn, $sql);

// Group reviews by book
$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $books[$row['title']]['cover_image'] = $row['cover_image'];
    $books[$row['title']]['reviews'][] = [
        'username' => $row['username'],
        'comment' => $row['comment'],
        'rating' => $row['rating']
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Reviews</title>
    <style>
        .review-box {
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
            padding: 15px;
        }
        .book-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .book-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .book-info img {
            width: 60px;
            height: 90px;
            object-fit: cover;
            border-radius: 4px;
        }
        .rating {
            font-weight: bold;
            font-size: 16px;
            color: #f39c12;
        }
        .comment-section {
            margin-top: 12px;
            padding-left: 10px;
        }
        .comment-item {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.4;
        }
        .comment-item strong {
            color: #2c3e50;
        }
    </style>
</head>
<body>
<h1>All Reviews</h1>

<?php if (!empty($books)): ?>
    <?php foreach ($books as $title => $data): ?>
        <div class="review-box">
            <!-- Book Thumbnail + Title + Avg Rating -->
            <div class="book-header">
                <div class="book-info">
                    <img src="<?= htmlspecialchars($data ['cover_image']) ?>" alt="Book Cover">
                    <div>
                        <strong><?= htmlspecialchars($title) ?></strong>
                    </div>
                </div>
                <div class="rating">
                    <?php 
                        $avg = array_sum(array_column($data['reviews'], 'rating')) / count($data['reviews']);
                        echo number_format($avg, 1) . " ★";
                    ?>
                </div>
            </div>

            <!-- Comments -->
            <div class="comment-section">
                <?php foreach ($data['reviews'] as $review): ?>
                    <div class="comment-item">
                        <strong><?= htmlspecialchars($review['username']) ?>:</strong> 
                        <?= htmlspecialchars($review['comment']) ?> 
                        <span style="color:#999; font-size:12px;">(<?= $review['rating'] ?> ★)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No reviews yet.</p>
<?php endif; ?>

</body>
</html>