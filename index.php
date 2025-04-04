<?php
session_start();

// output buffering to capture page content
ob_start();

// Homepage buttons depending on whether the user is logged in
$buttonHTML = '';
if (isset($_SESSION['user_id'])) {
    // If logged in show Make a Search and demo button
    $buttonHTML = <<< _LOGGEDIN
        <a href="search_form.php" class="btn">🔍 Make a Search</a>
        <a href="search_form.php?demo=1&protein=glucose-6-phosphatase&taxon=Aves&min_len=100&max_len=500&limit=10" class="btn">Try Demo Run</a>
_LOGGEDIN;
} else {
    // If not logged in show Login and demo button
    $buttonHTML = <<< _LOGGEDOUT
        <a href="login_user.php" class="btn">🔐 Log In</a>
        <a href="search_form.php?demo=1&protein=glucose-6-phosphatase&taxon=Aves&min_len=100&max_len=500&limit=10" class="btn">Try Demo Run</a>
_LOGGEDOUT;
}

// Output the main hero section (image + welcome text + buttons)
echo<<< _CONTENT
<div class="hero-image">
  <div class="hero-text">
    <h1>Welcome to Protein Swirl</h1>
    <p>
        Explore and analyse protein families across different species groups. View sequences, motifs, and conservation metrics — all in one place.
    </p>
    <p>
        🔐 You'll need to log in to run your own searches. <br> Not ready to sign up? Try the <strong>demo</strong> below to see an example analysis of <em>glucose-6-phosphatase</em> in birds (Aves).
    </p>
    <div class="hero-buttons">
        $buttonHTML
    </div>
  </div>
</div>
_CONTENT;

// Capture all buffered output
$pageContent = ob_get_clean();
// Set the page title
$pageTitle = "Protein Swirl";

// include the base layout
include './features/base_layout.php';
?>