<?php
$pageTitle = "Help & Context";

ob_start();
echo <<< _HELP
<div class="content-container">
    <h1>Help & Biological Context</h1>
    <p><strong>Protein Swirl</strong> is a tool designed to help explore and analyse protein sequences from different taxonomic groups. Whether you're looking to check similarities between species or spot conserved motifs, this tool tries to make that easy and informative.</p>

    <h2>What You Can Do</h2>
    <ul>
        <li><strong>Search for a protein family</strong> — provide a keyword (e.g. "glucose-6-phosphatase") and a taxonomic group (e.g. "Mammalia")</li>
        <li><strong>Filter by sequence length</strong> — optionally filter short or long proteins</li>
        <li><strong>Visualise outputs</strong> — it generates:
            <ul>
                <li>Multiple sequence alignments (Clustal Omega)</li>
                <li>Guide trees (sequence similarity)</li>
                <li>Conservation plots (using plotcon)</li>
                <li>Motif detection and frequency analysis (via patmatmotifs from EMBOSS)</li>
            </ul>
        </li>
        <li><strong>Download all results</strong> for future analyses</li>
    </ul>

    <h2>Demo vs. Full Access</h2>
    <p>
        Anyone can try the demo search (using a fixed dataset of glucose-6-phosphatase in Aves). However, to make your own searches, you need to <a href="login_user.php">create an account and log in</a>. This helps keep the server load reasonable and lets you come back to saved queries later.
    </p>

    <h2>Why Might This Be Useful?</h2>
    <p>
        This tool could help with:
        <ul>
            <li>Understanding how conserved a protein family is within a clade</li>
            <li>Spotting species-specific motifs or outliers</li>
            <li>Generating alignment files for downstream phylogenetics</li>
            <li>Comparing evolutionary conservation across species</li>
        </ul>
    </p>

    <p>I hope Protein Swirl helps bring your protein analyses to life 🌱</p>
</div>
_HELP;

$pageContent = ob_get_clean();
include './features/base_layout.php';
?>
