<?php
session_start();
require_once 'db/db_connection.php'; 
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js" integrity="sha512-tBzZQxySO5q5lqwLWfu8Q+o4VkTcRGOeQGVQ0ueJga4A1RKuzmAu5HXDOXLEjpbKyV7ow9ympVoa6wZLEzRzDg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
echo "<script src='assets/js/jsphylosvg-min.js'></script>";

function add_analysis($run_id, $type, $file_path, $description) {
    if (file_exists($file_path)) {
        $cmd = "python3 scripts/pop_analyses.py $run_id $type $file_path \"$description\"";
        shell_exec($cmd);
    }
}


$run_id = uniqid("run_");
echo $run_id;
$protein = $_POST['protein'];
$taxon = $_POST['taxon'];
# https://dev.to/lavary/php-double-question-marks-null-coalescing-operator-explained-49d7
// limit default set to 10
$limit = $_POST['limit'] ?? 10;

// Check if the user checked the Filter Sequence Length checkbox
$use_filter = isset($_POST['use_length_filter']);

// if use filter is set, assign their values to min and max otherwise set them to default
# https://www.php.net/manual/en/language.operators.comparison.php
$min_len = $use_filter && isset($_POST['min_len']) ? (int) $_POST['min_len'] : 0;
$max_len = $use_filter && isset($_POST['max_len']) ? (int) $_POST['max_len'] : 100000;

// Validate: make sure min is not greater than max
if ($min_len > $max_len) {
    die("Error!!! Minimum length cannot be greater than maximum length.");
}

// escape characters that can trick the shell command
$command = ("python3 scripts/get_sequences.py \"$protein\" \"$taxon\" $limit $min_len $max_len $run_id");
echo $command;

// execute via shell and return output as string
// output here is the output from the python script that is shown in comand line
// https://www.php.net/manual/en/function.shell-exec.php
$output = shell_exec($command);

echo "<h3>Sequences fetched for: $protein in $taxon</h3>";
echo "<pre>$output</pre>";
// error_log($output);

# Clustalo analysis
$input_fasta = "scripts/output/$run_id/sequences.fasta";
add_analysis($run_id, 'custom', $input_fasta, "Fetched sequences (FASTA)");


$alignment_out = "scripts/output/$run_id/alignment.aln";
$distmat_out = "scripts/output/$run_id/identity_matrix.txt";
$tree_out = "scripts/output/$run_id/guide_tree.dnd";

// ClustalO command
$clustal_cmd = "bash scripts/run_clustalo.sh $input_fasta $alignment_out $distmat_out $tree_out";

$clustalo_output = shell_exec($clustal_cmd);

echo "<h3>ClustalO alignment completed</h3>";
echo "<pre>$clustalo_output</pre>";

if (file_exists($alignment_out)) {
    $alignment_output = file_get_contents($alignment_out);
    echo "<pre>$alignment_output</pre>";
}

add_analysis($run_id, 'clustalo', $alignment_out, "ClustalO alignment file");
add_analysis($run_id, 'clustalo', $tree_out, "Phylogenetic tree (Newick format)");
add_analysis($run_id, 'clustalo', $distmat_out, "Sequence identity matrix (text)");


// Plotcon Analysis
$plotcon_out = "scripts/output/$run_id/conservation";
$plotcon_cmd = "bash scripts/run_plotcon.sh $alignment_out $plotcon_out";
shell_exec($plotcon_cmd);

// File that will be created by plotcon
$plotcon_output = "scripts/output/$run_id/conservation.1.png";

if (file_exists($plotcon_output)) {
    echo "<h3>Conservation Plot</h3>";
    echo "<img src='$plotcon_output' alt='Conservation Plot' style='width: 100%; max-width: 600px;'>";
} else {
    echo "<p style='color: red;'>Conservation plot not found: $plotcon_output</p>";
}
add_analysis($run_id, 'plotcon', $plotcon_output, "Conservation plot (plotcon)");

// Run patmatmotifs
$motif_script = "scripts/run_patmatmotifs.sh";
$run_motifs_cmd = "bash $motif_script $input_fasta $run_id";
$motif_output = shell_exec($run_motifs_cmd);

echo "<h3>Motif Analysis Output</h3>";
echo "<pre>$motif_output</pre>";

// Run the Python script to generate motif frequency plot
$plot_script = "scripts/plot_motif_freq.py";
$run_plot_cmd = "python3 $plot_script $run_id";
shell_exec($run_plot_cmd);

// Path to the generated image
$motif_img = "scripts/output/$run_id/motif_frequency.png";

// Debugging output
echo "<pre>Plot command: $run_plot_cmd</pre>";

// Display the plot image
if (file_exists($motif_img)) {
    echo "<h3>Motif Frequency Plot</h3>";
    echo "<img src='$motif_img' style='max-width: 100%; height: auto;' />";
} else {
    echo "<p style='color:red;'>Plot not found at: $motif_img</p>";
}
add_analysis($run_id, 'motif', $motif_img, "Motif frequency bar plot");

echo "<h3>Phylogenetic Tree</h3>";
echo "<div id='phylo_tree' style='width: 100%; height: 600px; border: 1px solid #ccc;'></div>";
if (file_exists($tree_out)) {
    $tree_data = trim(preg_replace('/\s+/', '', file_get_contents($tree_out))); # convert clustalo to more readable by the js library
    $escaped_tree = htmlspecialchars($tree_data, ENT_QUOTES);
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var newick = '$escaped_tree';
            new Smits.PhyloCanvas(
                newick,
                'phylo_tree',
                800,
                600,
                'rectangular'
            );
        });
    </script>";
} else {
    echo "<p style='color:red;'>No tree file found at $tree_out</p>";
}

// Generate identity matrix plot

$matrix_script = "scripts/plot_identity_matrix.py";
$matrix_cmd = "python3 $matrix_script $run_id";
shell_exec($matrix_cmd);

$matrix_img = "scripts/output/$run_id/identity_matrix.png";

if (file_exists($matrix_img)) {
    echo "<h3>Sequence Identity Matrix</h3>";
    echo "<img src='$matrix_img' alt='Identity Matrix' style='max-width: 100%; height: auto;' />";
} else {
    echo "<p style='color:red;'>Identity matrix plot not found.</p>";
}
add_analysis($run_id, 'clustalo', $matrix_img, "Sequence identity matrix heatmap");

echo "<h3>Available Analysis Outputs</h3>";

$analysis_sql = "SELECT id, type, result_path, label, file_type, created_at FROM Analyses WHERE search_id = ?";
$stmt = $pdo->prepare($analysis_sql);
$stmt->execute([$run_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($results) > 0) {
    echo "<table border='1' cellpadding='6'>
    <tr><th>Type</th><th>Description</th><th>File Type</th><th>Download</th><th>Created</th></tr>";
    foreach ($results as $row) {
        $path = $row['result_path'];
        $filename = basename($path);
        echo "<tr>
            <td>{$row['type']}</td>
            <td>{$row['label']}</td>
            <td>{$row['file_type']}</td>
            <td><a href='$path' download='$filename'>Download</a></td>
            <td>{$row['created_at']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No analysis outputs found.</p>";
}


echo "<a href='index.php'>Back to Homepage</a>";

?>