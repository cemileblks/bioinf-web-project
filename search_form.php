<?php
session_start();

// If user submitted the demo form, skip searching and redirect to the pre generated results
$isDemo = isset($_GET['demo']);
if ($isDemo && isset($_GET['submitted'])) {
    $demo_run_id = 'run_67eeaf4722722'; // Your pre-generated demo run ID
    header("Location: view_query.php?run_id=$demo_run_id&demo=1");
    exit;
}

// Check if this is a demo request and use params from url using get
$protein = $_GET['protein'] ?? '';
$taxon = $_GET['taxon'] ?? '';
$limit = $_GET['limit'] ?? '10';
$min_len = $_GET['min_len'] ?? '';
$max_len = $_GET['max_len'] ?? '';
// check if the checkbox for length filter is checked
$checked = ($min_len !== '' && $max_len !== '') ? 'checked' : '';

// Display demo banner if this is a demo run
$banner = '';
if ($isDemo) {
    $banner = <<< _BANNER
    <div class="demo-banner">
        👋 You're viewing a <strong>demo search</strong> pre-filled with glucose-6-phosphatase proteins from Aves. Click submit to explore the results!
    </div>
    _BANNER;
}

// Set form action + method based on demo status
$form_action = $isDemo ? 'search_form.php' : 'handle_query.php';
$form_method = $isDemo ? 'get' : 'post';

// Start output buffering 
ob_start();
echo $banner; // display banner if there
echo <<< _FORM
<h2 class='form-heading'>Search for proteins by family and taxonomic group</h2>
<form action="$form_action" method="$form_method" class="search-form">
    <input type="hidden" name="demo" value="1">
    <input type="hidden" name="submitted" value="1">
    
    <div class="form-group">
        <label for="protein">Protein Family:</label>
        <input type="text" name="protein" id="protein" class="input-field" value="$protein" required>
    </div>

    <div class="form-group">
        <label for="taxon">Taxonomic Group:</label>
        <input type="text" name="taxon" id="taxon" class="input-field" value="$taxon" required>
    </div>

    <fieldset>
        <legend>⚙️ Optional Parameters</legend>

        <div class="form-group">
            <label for="limit">Number of sequences (default 10):</label>
            <input type="number" name="limit" id="limit" class="input-field" min="3" value="$limit">
            <p class="input-note">Note, minimum allowed: <strong>3 sequences</strong></p>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="use_length_filter" id="use_length_filter" $checked>
                Filter sequences by Length
            </label><br>
            <input type="number" name="min_len" value="$min_len" min="0" placeholder="Min Length" class="input-field">
            <input type="number" name="max_len" value="$max_len" min="0" placeholder="Max Length" class="input-field">
        </div>
    </fieldset>

    <button type="submit" class="btn-submit">Submit Search</button>
</form>
_FORM;

// Capture and assign the form content to the layout system
$pageContent = ob_get_clean();
$pageTitle = "Search Proteins";
// Include base layout to render page structure
include './features/base_layout.php';