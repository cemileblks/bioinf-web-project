<?php
session_start();
require_once 'db/db_connection.php';

$run_id = $_GET['run_id'] ?? null;
if (!$run_id) {
    die("❌ No run ID specified.");
}

// Check if user owns this query (for security)
if (!isset($_SESSION['user_id'])) {
    die("❌ Please <a href='login_user.php'>log in</a> to view your saved query.");
}

$stmt = $pdo->prepare("SELECT * FROM Queries WHERE search_id = ? AND user_id = ?");
$stmt->execute([$run_id, $_SESSION['user_id']]);
$query = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$query) {
    die("⚠️ No such query found or you don't have access.");
}

// === Sequences ===
$seq_stmt = $pdo->prepare("SELECT refseq_id, species, sequence FROM Sequences WHERE search_id = ?");
$seq_stmt->execute([$run_id]);
$sequences = $seq_stmt->fetchAll(PDO::FETCH_ASSOC);

// === Motifs ===
$motif_stmt = $pdo->prepare("SELECT * FROM Motifs WHERE search_id = ?");
$motif_stmt->execute([$run_id]);
$motifs = $motif_stmt->fetchAll(PDO::FETCH_ASSOC);

// === Analyses ===
$analysis_stmt = $pdo->prepare("SELECT * FROM Analyses WHERE search_id = ?");
$analysis_stmt->execute([$run_id]);
$analyses = $analysis_stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Build HTML ---
ob_start();

echo "<h1>Previous Query: {$run_id}</h1>";
echo "<p><strong>Protein:</strong> {$query['protein_family']} | <strong>Taxon:</strong> {$query['taxon']} | <strong>Date:</strong> {$query['created_at']}</p>";

// Sequences
echo "<h2>🔬 Sequences</h2>";
if ($sequences) {
    echo "<table border='1' cellpadding='6'><tr><th>RefSeq</th><th>Species</th><th>Length</th></tr>";
    foreach ($sequences as $s) {
        $len = strlen($s['sequence']);
        echo "<tr><td>{$s['refseq_id']}</td><td>{$s['species']}</td><td>$len</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No sequences found.</p>";
}

// Motifs
echo "<h2>🎯 Motifs</h2>";
if ($motifs) {
    echo "<table border='1' cellpadding='6'><tr><th>RefSeq</th><th>Motif</th><th>PROSITE</th><th>Start</th><th>End</th></tr>";
    foreach ($motifs as $m) {
        echo "<tr><td>{$m['sequence_id']}</td><td>{$m['motif_name']}</td><td>{$m['prosite_id']}</td><td>{$m['start_pos']}</td><td>{$m['end_pos']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No motifs found.</p>";
}

// Analyses
echo "<h2>📊 Analysis Files</h2>";
if ($analyses) {
    echo "<table border='1' cellpadding='6'><tr><th>Type</th><th>Label</th><th>Download</th><th>Created</th></tr>";
    foreach ($analyses as $a) {
        $file = basename($a['result_path']);
        echo "<tr><td>{$a['type']}</td><td>{$a['label']}</td><td><a href='{$a['result_path']}' download='$file'>Download</a></td><td>{$a['created_at']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No analysis outputs found.</p>";
}

$pageContent = ob_get_clean();
$pageTitle = "View Query $run_id";
include './features/base_layout.php';
