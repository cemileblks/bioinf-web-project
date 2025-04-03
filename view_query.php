<?php
session_start();
require_once 'db/db_connection.php';

$run_id = $_GET['run_id'] ?? null;
if (!$run_id) {
    $pageTitle = "Error";
    ob_start();
    echo "<p>❌ No run ID specified.</p>";
    $pageContent = ob_get_clean();
    include './features/base_layout.php';
    exit;
}

$isDemo = isset($_GET['demo']);
if (!$isDemo && !isset($_SESSION['user_id'])) {
    $pageTitle = "Login Required";
    ob_start();
    echo <<< _LOGIN
    <div class='login-warning'>
        <h2>❌ Access Denied</h2>
        <p>Please log in to view your saved query.</p>
        <p><a href="login_user.php" class="btn">Log in</a></p>
    </div>
    _LOGIN;
    $pageContent = ob_get_clean();
    include './features/base_layout.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM Queries WHERE search_id = ?" . ($isDemo ? '' : ' AND user_id = ?'));
$stmt->execute($isDemo ? [$run_id] : [$run_id, $_SESSION['user_id']]);
$query = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$query) {
    $pageTitle = "Query Not Found";
    ob_start();
    echo "<p>⚠️ No such query found.</p>";
    $pageContent = ob_get_clean();
    include './features/base_layout.php';
    exit;
}

$seq_stmt = $pdo->prepare("SELECT refseq_id, species, sequence FROM Sequences WHERE search_id = ?");
$seq_stmt->execute([$run_id]);
$sequences = $seq_stmt->fetchAll(PDO::FETCH_ASSOC);

$motif_stmt = $pdo->prepare("
    SELECT m.*, s.refseq_id, s.species
    FROM Motifs m
    JOIN Sequences s ON m.sequence_id = s.id
    WHERE m.search_id = ?
");
$motif_stmt->execute([$run_id]);
$motifs = $motif_stmt->fetchAll(PDO::FETCH_ASSOC);


$analysis_stmt = $pdo->prepare("SELECT * FROM Analyses WHERE search_id = ?");
$analysis_stmt->execute([$run_id]);
$analyses = $analysis_stmt->fetchAll(PDO::FETCH_ASSOC);

// Start page output
ob_start();
echo "<div class='content-container'>";
echo "<h1>🔍 Search Results: {$query['protein_family']} in {$query['taxon']}</h1>";
echo "<p><strong>Date:</strong> {$query['created_at']}</p>";

// Sequences
echo "<h2>🔬 Sequences Retrieved</h2>";
echo "<p><strong>Total:</strong> " . count($sequences) . "</p>";
if ($sequences) {
    echo "<table border='1' cellpadding='6'><tr><th>RefSeq ID</th><th>Species</th><th>Length</th></tr>";
    foreach ($sequences as $s) {
        $len = strlen($s['sequence']);
        echo "<tr><td>{$s['refseq_id']}</td><td>{$s['species']}</td><td>{$len}</td></tr>";
    }
    echo "</table>";
    echo "<p><a href='scripts/output/{$run_id}/sequences.fasta' download>⬇️ Download FASTA</a></p>";
} else {
    echo "<p>No sequences stored.</p>";
}

// Tree Output
$tree_path = "scripts/output/$run_id/guide_tree.dnd";
if (file_exists($tree_path)) {
    $tree_data = trim(preg_replace('/\s+/', '', file_get_contents($tree_path)));
    $escaped_tree = htmlspecialchars($tree_data, ENT_QUOTES);
    echo "<h2>ClustalO Guide Tree</h2>";
    echo "<p>This is a <strong>guide tree</strong> based on sequence similarity. It is not a phylogenetic tree.</p>";
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>';
    echo "<script src='assets/js/jsphylosvg-min.js'></script>";
    echo "<div id='phylo_tree' style='width: 100%; height: 600px; border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;'></div>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var newick = '$escaped_tree';
            new Smits.PhyloCanvas(newick, 'phylo_tree', 800, 600, 'rectangular');
        });
    </script>";
}

// Show Identity Matrix
$identity_img = "scripts/output/$run_id/identity_matrix.png";
if (file_exists($identity_img)) {
    echo "<h2>Identity Matrix</h2>";
    echo "<img src='$identity_img' style='max-width: 100%; border: 1px solid #ccc; padding: 10px;'>";
    echo "<p><a href='$identity_img' download>⬇️ Download Identity Matrix</a></p>";
}

// Plotcon
$plotcon_img = "scripts/output/$run_id/conservation.1.png";
if (file_exists($plotcon_img)) {
    echo "<h2>📈 Conservation Plot (Plotcon)</h2>";
    echo "<img src='$plotcon_img' style='max-width: 100%; border: 1px solid #ccc; padding: 10px;'>";
    echo "<p><a href='$plotcon_img' download>⬇️ Download Conservation Plot</a></p>";
}

// Motif table
echo "<h2>Motif Hits</h2>";
if ($motifs) {
    echo "<table border='1' cellpadding='6'>
    <tr><th>RefSeq</th><th>Species</th><th>Motif Name</th><th>Start</th><th>End</th></tr>";

    foreach ($motifs as $m) {
        echo "<tr>
            <td>{$m['refseq_id']}</td>
            <td>{$m['species']}</td>
            <td>{$m['motif_name']}</td>
            <td>{$m['start_pos']}</td>
            <td>{$m['end_pos']}</td>
        </tr>";
    }

    echo "</table>";
    echo "<p><a href='scripts/output/$run_id/motif_results.tsv' download>⬇️ Download Motif TSV</a></p>";
} else {
    echo "<p>No motif hits found.</p>";
}


// Motif Frequency Plot
$freq_img = "scripts/output/$run_id/motif_frequency.png";
if (file_exists($freq_img)) {
    echo "<h2>📊 Motif Frequency</h2>";
    echo "<img src='$freq_img' style='max-width: 100%; border: 1px solid #ccc; padding: 10px;'>";
    echo "<p><a href='$freq_img' download>⬇️ Download Frequency Plot</a></p>";
}

// Analysis file list
echo "<h2>📂 Analysis Files</h2>";
if ($analyses) {
    echo "<table border='1' cellpadding='6'><tr><th>Type</th><th>Description</th><th>Download</th><th>Created</th></tr>";
    foreach ($analyses as $a) {
        $file = basename($a['result_path']);
        echo "<tr>
            <td>{$a['type']}</td>
            <td>{$a['label']}</td>
            <td><a href='{$a['result_path']}' download='$file'>Download</a></td>
            <td>{$a['created_at']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No analysis outputs found.</p>";
}

// Resources
echo <<< _RESOURCES
<div class='external-links'>
    <h2>🔗 External Resources</h2>
    <ul>
        <li><a href="https://www.uniprot.org/" target="_blank">UniProt</a> — for additional protein annotations</li>
        <li><a href="https://prosite.expasy.org/" target="_blank">PROSITE</a> — to investigate motif patterns</li>
        <li><a href="https://blast.ncbi.nlm.nih.gov/Blast.cgi?PAGE=Proteins" target="_blank">NCBI BLASTP</a> — to compare your sequences</li>
    </ul>
</div>
_RESOURCES;

echo "<p style='margin-top: 30px;'><a href='index.php'>⬅️ Back to Homepage</a></p>";
echo "</div>";
$pageContent = ob_get_clean();
$pageTitle = "Query Results: {$query['protein_family']}";
include './features/base_layout.php';
