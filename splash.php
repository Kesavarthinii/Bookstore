<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bookit - Welcome</title>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
<style>
/* --- your exact CSS (unchanged) --- */
body {
    margin: 0;
    padding: 0;
    font-family: 'Merriweather', serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: #fcfaf6;
    position: relative;
    overflow: hidden;
}
.background-layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('assets/background.png');
    background-size: cover;
    background-position: center;
    will-change: transform;
    transform: scale(1.05);
}
.welcome-container {
    position: relative;
    z-index: 2;
    background-color: rgba(252, 250, 246, 0.9);
    border-radius: 20px;
    padding: 60px 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    width: 90%;
    max-width: 550px;
    animation: fadeIn 1.5s ease-out forwards;
}
.app-name {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.5em, 8vw, 3.5em);
    font-weight: 700;
    color: #2e3d33;
    margin: 0;
    animation: slideInUp 1s ease-out forwards;
    opacity: 0;
    animation-delay: 0.5s;
}
.tagline {
    font-size: clamp(1em, 3vw, 1.3em);
    color: #555;
    margin-top: 15px;
    margin-bottom: 30px;
    font-weight: 300;
    line-height: 1.4;
    animation: slideInUp 1s ease-out forwards;
    opacity: 0;
    animation-delay: 0.8s;
}
.action-button {
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    background-color: #2e3d33;
    color: #fff;
    font-family: 'Playfair Display', serif;
    font-weight: bold;
    font-size: 1.1em;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    text-decoration: none;
    animation: fadeIn 1s ease-out forwards;
    opacity: 0;
    animation-delay: 1.2s;
}
.action-button:hover {
    background-color: #1f2a22;
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>
    <div class="background-layer"></div>
    <div class="welcome-container">
        <h1 class="app-name">BOOKIT</h1>
        <p class="tagline">Your reading journey, perfectly organized.</p>
        <a href="login.php" class="action-button">Get Started</a>
    </div>
</body>
</html>
