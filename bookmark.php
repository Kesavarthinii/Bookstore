<?php
session_start();
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='text-align:center;margin-top:50px;'>Please <a href='login.php'>login</a> to view your bookmarks.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch bookmarked books
$sql = "SELECT b.* FROM books b
        INNER JOIN bookmarks bm ON b.id = bm.book_id
        WHERE bm.user_id = ?
        ORDER BY bm.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookmarks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Bookmarks</title>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Merriweather', serif;
    background-color: #fcfaf6;
}

.main-container {
    padding: 1px 40px 50px;
}

h1 {
    font-family: 'Playfair Display', serif;
    font-size: 2.3em;
    color: #333;
    text-align: center;
    margin: 30px 0;
}

.book-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 600px;
    margin: 0 auto;
}

.book-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s;
}

.book-card:hover {
    transform: scale(1.02);
}

.book-card img {
    width: 100px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
}

.book-info {
    flex: 1;
    cursor: pointer;
}

.book-title {
    font-weight: bold;
    font-size: 1.2em;
    color: #222;
    margin-bottom: 5px;
}

.book-author {
    font-size: 0.95em;
    color: #666;
}

.remove-btn {
    background: #b91c1c;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
}

.remove-btn:hover {
    background: #7f1d1d;
}

/* Modal overlay */
#confirmModal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

/* Modal content box */
#confirmModal .modal-content {
    background: #fff;
    padding: 25px 30px;
    border-radius: 10px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    animation: popIn 0.2s ease;
}

/* Buttons container */
#confirmModal .modal-buttons {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 20px;
}

/* Modal buttons */
#confirmModal button {
    padding: 8px 18px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

#confirmModal .yes-btn {
    background: #b91c1c;
    color: #fff;
}

#confirmModal .yes-btn:hover {
    background: #7f1d1d;
}

#confirmModal .no-btn {
    background: #ccc;
}

#confirmModal .no-btn:hover {
    background: #999;
}

/* Popup animation */
@keyframes popIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

#no-bookmarks {
    text-align: center;
    font-size: 1.1em;
    color: #555;
    margin-top: 50px;
}
</style>
</head>
<body>

<div class="main-container">
    <h1>Your Bookmarks</h1>

    <div class="book-list" id="bookList">
    <?php if(count($bookmarks) > 0): ?>
        <?php foreach($bookmarks as $book): ?>
            <div class="book-card" data-book-id="<?= $book['id'] ?>">
                <img src="<?= htmlspecialchars($book['cover_image']) ?>" 
                     alt="<?= htmlspecialchars($book['title']) ?>" 
                     onclick="window.location.href='book.php?id=<?= $book['id'] ?>'">
                <div class="book-info" onclick="window.location.href='book.php?id=<?= $book['id'] ?>'">
                    <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                    <div class="book-author"><?= htmlspecialchars($book['author']) ?></div>
                </div>
                <button class="remove-btn">Remove</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div id="no-bookmarks">No bookmarks</div>
    <?php endif; ?>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal">
    <div class="modal-content">
        <p>Remove this book from your bookmarks?</p>
        <div class="modal-buttons">
            <button class="yes-btn">Yes</button>
            <button class="no-btn">No</button>
        </div>
    </div>
</div>

<script>
let selectedCard = null;

// Open modal on remove button click
document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        selectedCard = this.closest('.book-card');
        document.getElementById('confirmModal').style.display = 'flex';
    });
});

// Modal "No" button
document.querySelector('#confirmModal .no-btn').addEventListener('click', function() {
    document.getElementById('confirmModal').style.display = 'none';
    selectedCard = null;
});

// Modal "Yes" button
document.querySelector('#confirmModal .yes-btn').addEventListener('click', function() {
    if(!selectedCard) return;
    const bookId = selectedCard.getAttribute('data-book-id');

    fetch('remove_bookmark.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'book_id=' + encodeURIComponent(bookId)
    })
    .then(res => res.text())
    .then(res => {
        if(res.trim() === 'success') {
            selectedCard.remove(); // remove from page
            document.getElementById('confirmModal').style.display = 'none';

            // If no bookmarks left, show "No bookmarks"
            if(document.querySelectorAll('.book-card').length === 0) {
                const noDiv = document.createElement('div');
                noDiv.id = 'no-bookmarks';
                noDiv.innerText = 'No bookmarks';
                document.getElementById('bookList').appendChild(noDiv);
            }
        } else {
            alert('Failed to remove bookmark.');
        }
        selectedCard = null;
    })
    .catch(err => {
        alert('Error removing bookmark.');
        selectedCard = null;
        document.getElementById('confirmModal').style.display = 'none';
    });
});
</script>

</body>
</html>