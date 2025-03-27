<?php

session_start();

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
error_log($output);
echo "<a href='index.php'>Back to Homepage</a>";

?>