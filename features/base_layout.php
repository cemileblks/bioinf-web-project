<?php

// Must set $pageTitle and $pageContent before including this layout on page
$pageTitle = $pageTitle ?? "Protein Swirl 🌀";

echo <<< _BASEHEAD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>$pageTitle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
_BASEHEAD;

include 'navbar.php';

echo <<< _WRAPPER
<main class="container" style="padding: 30px;">
$pageContent
</main>
_WRAPPER;

include 'footer.php';

echo "</body></html>";
?>
