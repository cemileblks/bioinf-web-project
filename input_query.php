<?php
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
    <form action="input_query.php" method="post">
        <label for="protein">Protein Family</label><br>
        <input type="text" name="protein" id="protein" required><br><br>

        <label for="taxon">Taxonomic Group:</label><br>
        <input type="text" name="taxon" id="taxon" required><br><br>

_HEAD1;

echo <<< _TAIL1
        <input type="submit" value="Submit">
    </form>
</body>
</html>
_TAIL1;

?>