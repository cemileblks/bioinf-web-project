<?php
session_start();

ob_start();
echo <<< _CONTENT
<h1>📚 Credits & Acknowledgments</h1>
<p>Protein Swirl 🌀 was developed as part of an <strong>introductory course in Web Development and Database Design</strong> at the <em>University of Edinburgh</em>.</p>
<p>Code, concepts, and inspiration were drawn from the course materials, lectures, and various bioinformatics resources.</p>
<p>📂 Source available on <a href="https://github.com" target="_blank">GitHub</a></p>
_CONTENT;

$pageContent = ob_get_clean();
$pageTitle = "Credits";

include './features/footer.php';