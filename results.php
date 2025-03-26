<?php

session_start();

$protein = $_POST['protein'];
$taxon = $_POST['taxon'];
// $limit = $_POST['limit'] ?? 50;

// escape characters that can trick the shell command
$command = escapeshellcmd("python3 scripts/get_sequences.py \"$protein\" \"$taxon\" 10 web_output");

// execute via shell and return output as string
// output here is the output from the python script that is shown in comand line
$output = shell_exec($command);

echo "<h3>Sequences fetched for: $protein in $taxon</h3>";
echo "<pre>$output</pre>";
echo "<a href='index.php'>Back to Home</a>";


?>