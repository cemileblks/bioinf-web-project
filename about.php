<?php
$pageTitle = "About This Project";

ob_start();
echo <<< _ABOUT
<div class="content-container">
    <h1>About Protein Swirl</h1>
    <p><strong>Protein Swirl</strong> is a dynamic web application developed as part of the assessment for <em>Introduction to Website and Database Design (BILG11016)</em> at the University of Edinburgh.</p>
    
    <p>The goal of the project was to create an interactive, database-driven website that allows users to explore protein sequences within a specific taxonomic group — with the example dataset being <strong>glucose-6-phosphatase</strong> proteins from <strong>Aves</strong>.</p>

    <h2>Technical Overview</h2>
    <ul>
        <li>🧩 Built with PHP, HTML, CSS, and a MySQL backend</li>
        <li>🐍 Integrated with external Python scripts (e.g. for sequence fetching, alignment, and plotting)</li>
        <li>🧬 EMBOSS and Clustal Omega are used under the hood for sequence analysis</li>
        <li>🧠 Backend logic follows clean separation between database, scripts, and frontend</li>
        <li>📊 Output includes conservation plots, sequence identity matrices, and motif frequency charts</li>
        <li>📁 Fully logged queries and downloadable analysis outputs for each run</li>
    </ul>

    <h2>Structure</h2>
    <p>The project consists of a login system, search interface, analysis view, and some visualisations.</p>

    <h2>GitHub</h2>
    <p>All code is hosted here: 
        <a href="https://github.com/B272229-2024/ICA_IWDD" target="_blank" rel="noopener noreferrer">View on GitHub</a>
    </p>

    <p>Built with love, late nights, and too much coffee ☕</p>
</div>
_ABOUT;

$pageContent = ob_get_clean();
include './features/base_layout.php';
?>