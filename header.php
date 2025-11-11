<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// Detect active page
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 40px;
        height: 90px;
        background-color: #fff;
        color: #2e3d33;
        font-size: 16px;
        border-bottom: 1px solid #eee; /* keeps alignment steady */
    }

    .logo {
        display: flex;
        align-items: center;
        font-size: 22px;
        font-weight: bold;
        color: darkgreen;
    }

    .logo img {
        height: 35px;
        width: auto;
        margin-right: 10px;
    }

    nav {
        display: flex;
        align-items: center;
    }

    nav a {
        margin-right: 20px;
        text-decoration: none;
        color: #2e3d33;
        font-size: 16px;
        transition: color 0.3s;
    }

    nav a.active {
        color: green;
        font-weight: bold;
    }

    nav a:last-child {
        margin-right: 0;
    }
</style>

<header>
    <div class="logo">
        <img src="assets/open-book.png" alt="Bookit Logo">
        Bookit
    </div>
    <nav>
        <a href="home.php" class="<?= $currentPage == 'home.php' ? 'active' : '' ?>">Home</a>
        <a href="genres.php" class="<?= $currentPage == 'genres.php' ? 'active' : '' ?>">Genres</a>
        <a href="bookmark.php" class="<?= $currentPage == 'bookmark.php' ? 'active' : '' ?>">Bookmarks</a>
        <a href="reviews.php" class="<?= $currentPage == 'reviews.php' ? 'active' : '' ?>">Reviews</a>
        <?php if ($email): ?>
            <a href="profile.php" class="<?= $currentPage == 'profile.php' ? 'active' : '' ?>">Profile</a>
        <?php else: ?>
            <a href="login.php" class="<?= $currentPage == 'login.php' ? 'active' : '' ?>">Login</a>
        <?php endif; ?>
    </nav>
</header>
