<?php

// Must set $pageTitle and $pageContent before including this layout on page
$pageTitle = $pageTitle ?? "Protein Swirl";

echo <<< _BASEHEAD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>$pageTitle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
_BASEHEAD;

include 'navbar.php';

echo <<< _WRAPPER
<main class="container">
$pageContent
</main>
_WRAPPER;

include 'footer.php';
echo '<script src="./assets/js/script.js"></script>';
echo '</body></html>';
?>
