<?php
session_start();
include 'config.php';
include 'header.php';

// Fetch latest 5 books for new arrivals
$newArrivalsQuery = "SELECT * FROM books ORDER BY uploaded_at DESC LIMIT 5";
$newArrivalsResult = mysqli_query($conn, $newArrivalsQuery);

// Fetch all books except the latest 5
$allBooksQuery = "SELECT * FROM books ORDER BY uploaded_at DESC LIMIT 5, 100";
$allBooksResult = mysqli_query($conn, $allBooksQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bookit - Your Personal Library</title>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Merriweather', serif;
        background-color: #fcfaf6;
    }

    .main-container {
        padding: 10px 40px 50px;
    }

    .welcome-section h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5em;
        color: #333;
    }

    .section {
        margin-top: 50px;
    }

    .section h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.8em;
        color: #555;
        border-bottom: 2px solid #d8d2c4;
        padding-bottom: 10px;
    }

    .book-list {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        margin-top: 20px;
    }

    .book-item {
        text-align: center;
        width: 150px;
        cursor: pointer;
    }

    .book-item img {
        width: 150px;
        height: 225px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .book-item:hover img {
        transform: scale(1.05);
    }

    .book-item h3 {
        font-size: 1em;
        color: #333;
        margin: 10px 0 5px;
    }

    .book-item p {
        font-size: 0.9em;
        color: #888;
        margin: 0;
    }

    /* Modal styling */
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgba(0,0,0,0.6); 
    }

    .modal-content {
        background-color: #fcfaf6;
        margin: 10% auto; 
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        max-width: 600px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        position: relative;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        cursor: pointer;
    }

    .modal-content img {
        width: 200px;
        height: 300px;
        object-fit: cover;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .modal-content h2 {
        margin: 10px 0 5px;
    }

    .modal-content p {
        color: #555;
        margin-bottom: 15px;
    }

    .read-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #333;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.2s;
    }

    .read-btn:hover {
        background-color: #555;
    }
</style>
</head>
<body>

<div class="main-container">
    <div class="welcome-section">
        <h1>Welcome to Your Personal Library</h1>
        <p>Explore our collection of over a million books.</p>
    </div>

    <!-- New Arrivals -->
    <div class="section new-arrivals-section">
        <h2>New Arrivals</h2>
        <div class="book-list">
            <?php while ($book = mysqli_fetch_assoc($newArrivalsResult)) { ?>
                <div class="book-item" onclick="openModal('<?php echo addslashes($book['title']); ?>','<?php echo addslashes($book['author']); ?>','<?php echo addslashes($book['description']); ?>','<?php echo $book['cover_image']; ?>','<?php echo $book['pdf_file']; ?>')">
                    <img src="<?php echo $book['cover_image']; ?>" alt="<?php echo $book['title']; ?>">
                    <h3><?php echo $book['title']; ?></h3>
                    <p><?php echo $book['author']; ?></p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- All Books -->
    <div class="section all-books-section">
        <h2>All Books</h2>
        <div class="book-list">
            <?php while ($book = mysqli_fetch_assoc($allBooksResult)) { ?>
                <div class="book-item" onclick="openModal('<?php echo addslashes($book['title']); ?>','<?php echo addslashes($book['author']); ?>','<?php echo addslashes($book['description']); ?>','<?php echo $book['cover_image']; ?>','<?php echo $book['pdf_file']; ?>')">
                    <img src="<?php echo $book['cover_image']; ?>" alt="<?php echo $book['title']; ?>">
                    <h3><?php echo $book['title']; ?></h3>
                    <p><?php echo $book['author']; ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
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
    </div>
</div>

<script>
function openModal(title, author, description, cover, pdf) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalAuthor').innerText = author;
    document.getElementById('modalDesc').innerText = description;
    document.getElementById('modalCover').src = cover;
    document.getElementById('modalReadBtn').href = pdf;
    document.getElementById('bookModal').style.display = "block";
}

function closeModal() {
    document.getElementById('bookModal').style.display = "none";
}

// Close modal if click outside content
window.onclick = function(event) {
    if (event.target == document.getElementById('bookModal')) {
        closeModal();
    }
}
</script>

</body>
</html>
