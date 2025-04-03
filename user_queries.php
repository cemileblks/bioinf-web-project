<?php
session_start();
require_once 'db/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("❌ Please <a href='login_user.php'>log in</a> to view your saved queries.");
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM Queries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$queries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Start output buffering 
ob_start();
echo "<div class='content-container'>";
echo <<< _HTML

<h1>📁 My Queries</h1>
_HTML;

if ($queries) {
    echo "<table border='1' cellpadding='6'>
        <tr><th>Run ID</th><th>Protein</th><th>Taxon</th><th>Sequences</th><th>Date</th></tr>";
    foreach ($queries as $q) {
        echo "<tr>
            <td><a href='view_query.php?run_id={$q['search_id']}'>{$q['search_id']}</a></td>
            <td>{$q['protein_family']}</td>
            <td>{$q['taxon']}</td>
            <td>{$q['no_of_sequences']}</td>
            <td>{$q['created_at']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>You haven't submitted any queries yet!</p>";
}
echo "</div>";
// Get the contents of the output buffer and turn it off
$pageContent = ob_get_clean();
// Set page title for base_layout.php template
$pageTitle = "My Queries";
include './features/base_layout.php';