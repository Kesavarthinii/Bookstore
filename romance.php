<?php
session_start();
include 'config.php';
include 'header.php';

$genre = 'romance';

// Fetch books from DB
$sql = "SELECT * FROM books WHERE genre = ? ORDER BY uploaded_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $genre);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= ucfirst($genre) ?> Books</title>
<style>
    body { font-family: Arial, sans-serif; background: #fcfaf6; padding: 20px; }
    h1 { text-align: center; margin-bottom: 30px; font-family: 'Playfair Display', serif; }
    .book-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
    .book-card { background: #fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 10px; width: 200px; text-align: center; transition: transform 0.3s; cursor: pointer; }
    .book-card:hover { transform: scale(1.05); }
    .book-card img { width: 150px; height: 220px; object-fit: cover; border-radius: 8px; }
    .book-title { font-weight: bold; margin-top: 10px; }
    .book-author { font-size: 0.9em; color: #666; }
    .book-description { font-size: 0.85em; color: #444; margin-top: 5px; height: 60px; overflow: hidden; text-overflow: ellipsis; }
    .back-btn { display: block; text-align: center; margin-bottom: 20px; }
    .back-btn a { text-decoration: none; color: #fff; background: #2e3d33; padding: 8px 16px; border-radius: 6px; transition: background 0.3s; }
    .back-btn a:hover { background: #444; }

    /* Modal */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); overflow: auto; }
    .modal-content { background: #fff; margin: 5% auto; padding: 20px; border-radius: 10px; width: 90%; max-width: 700px; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
    .modal-content img { width: 200px; height: 300px; object-fit: cover; margin-bottom: 15px; border-radius: 8px; }
    .modal-content h2 { margin: 10px 0 5px; }
    .modal-content h4 { margin: 5px 0; color: #555; }
    .modal-content p { color: #444; margin-bottom: 15px; }
    .close { position: absolute; top: 10px; right: 20px; font-size: 2rem; cursor: pointer; }
    .read-btn { display: inline-block; padding: 10px 20px; background: #2e3d33; color: #fff; border-radius: 5px; text-decoration: none; font-weight: bold; }
    .read-btn:hover { background: #444; }
    .bookmark-btn { margin-top: 10px; display: inline-block; padding: 8px 16px; background: #ff9800; color: #fff; border-radius: 5px; cursor: pointer; }
    .stars span { font-size: 1.5rem; cursor: pointer; color: #ccc; }
    .stars .selected { color: gold; }
    .comments { text-align: left; margin-top: 15px; }
    .comments p { border-bottom: 1px solid #ddd; padding: 5px 0; }
</style>
</head>
<body>

<div class="back-btn">
    <a href="genres.php">← Back to Genres</a>
</div>

<h1><?= ucfirst($genre) ?> Books</h1>
<div class="book-grid">
<?php if ($result->num_rows > 0): ?>
    <?php while($book = $result->fetch_assoc()): ?>
        <div class="book-card"
             onclick="openModal(<?= $book['id'] ?>,
                               '<?= addslashes($book['title']) ?>',
                               '<?= addslashes($book['author']) ?>',
                               '<?= addslashes($book['description']) ?>',
                               '<?= $book['cover_image'] ?>',
                               '<?= $book['pdf_file'] ?>')">
            <img src="<?= $book['cover_image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>">
            <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
            <div class="book-author"><?= htmlspecialchars($book['author']) ?></div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No books found in this genre.</p>
<?php endif; ?>
</div>

<!-- Modal -->
<div id="bookModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="modalCover" src="" alt="">
        <h2 id="modalTitle"></h2>
        <h4 id="modalAuthor"></h4>
        <p id="modalDesc"></p>
        <a id="modalReadBtn" class="read-btn" href="" target="_blank">Read Book</a>
        
        <!-- Bookmark -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <button class="bookmark-btn" onclick="addBookmark()">Add to Bookmark</button>
        <?php else: ?>
            <p><a href="login.php">Login to bookmark</a></p>
        <?php endif; ?>

        <!-- Rating -->
        <div class="stars" id="ratingStars"></div>

        <!-- Comment Form -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <textarea id="commentBox" rows="3" placeholder="Write a comment..."></textarea><br>
            <button onclick="submitReview()">Submit Review</button>
        <?php else: ?>
            <p><a href="login.php">Login to rate & comment</a></p>
        <?php endif; ?>

        <!-- Comments -->
        <div class="comments" id="commentsList">Loading comments...</div>

    </div>
</div>

<script>
let currentBookId = null;
let selectedRating = 0;

function openModal(id, title, author, description, cover, pdf) {
    currentBookId = id;
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalAuthor').innerText = author;
    document.getElementById('modalDesc').innerText = description;
    document.getElementById('modalCover').src = cover;
    document.getElementById('modalReadBtn').href = pdf;
    document.getElementById('bookModal').style.display = "block";

    loadComments();
    loadUserReview();
}

function loadUserReview() {
    fetch('get_user_review.php?book_id=' + currentBookId)
    .then(res => res.json())
    .then(data => {
        selectedRating = data.rating || 0;
        document.getElementById('commentBox').value = data.comment || "";
        loadStars();
    });
}

function closeModal() {
    document.getElementById('bookModal').style.display = "none";
}

window.onclick = function(event) {
    if (event.target == document.getElementById('bookModal')) {
        closeModal();
    }
}

// Load stars
function loadStars() {
    let starsDiv = document.getElementById('ratingStars');
    starsDiv.innerHTML = '';
    for (let i=1; i<=5; i++) {
        let star = document.createElement('span');
        star.innerHTML = '★';
        star.onclick = () => selectRating(i);
        star.classList.toggle('selected', i <= selectedRating);
        starsDiv.appendChild(star);
    }
}

function selectRating(r) {
    selectedRating = r;
    loadStars();
}

// Submit Review
function submitReview() {
    let comment = document.getElementById('commentBox').value;
    if (!selectedRating && !comment) {
        alert("Please select a rating or write a comment!");
        return;
    }

    fetch('review_handler.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `book_id=${currentBookId}&rating=${selectedRating}&comment=${encodeURIComponent(comment)}`
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        loadComments();
    });
}

// Load Comments
function loadComments() {
    fetch('get_comments.php?book_id=' + currentBookId)
    .then(res => res.text())
    .then(html => {
        document.getElementById('commentsList').innerHTML = html;
    });
}

// Bookmark
function addBookmark() {
    fetch('bookmark_handler.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `book_id=${currentBookId}`
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
    });
}
</script>

</body>
</html>

