<?php
session_start();
include './features/navbar.php';

echo <<< _HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credits - Protein Swirl</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <main class="credits-container">
        <h1>📚 Credits & Acknowledgments</h1>
    </main>
</body>
</html>
_HTML;

include './features/footer.php';
