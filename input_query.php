<?php
session_start();
echo <<< _HEAD1
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Enter your protein sequence and Taxon family</h2>
_HEAD1;

echo <<< _FORM
    <form action="../web_project/results.php" method="post">
        <label for="protein">Protein Family</label><br>
        <input type="text" name="protein" id="protein" required><br><br>

        <label for="taxon">Taxonomic Group:</label><br>
        <input type="text" name="taxon" id="taxon" required><br><br>

        <p>Optional parameters:</p>

        <label for="limit">Max Sequences:</label><br>
        <input type="number" name="limit" id="limit" min="0">
        <p>*10 by default</p><br>

        <input type="checkbox" name="use_length_filter" id="use_length_filter">
        Min Length: <input type="number" name="min_len" value="100" min="0">
        Max Length: <input type="number" name="max_len" value="500" min="0"><br><br>

        <input type="submit" value="Submit">
    </form>
_FORM;

echo <<< _TAIL1
</body>

</html>
_TAIL1;
