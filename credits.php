<?php
$pageTitle = "Statement of Credits";
ob_start();

echo <<< _HTML
    <div class="content-container">
    <h1>📚 Statement of Credits</h1>
    <p>This website was developed as part of the <strong>"Introduction to Website and Database Design (BILG11016)"</strong> course at the University of Edinburgh.</p>

    <p>Below is a breakdown of all the external resources and tools that were used throughout the development of this site, along with what I used them for.</p>

    <hr>

    <h2>Code Resources & References</h2>
    <ul>
        <li><strong>PHP to PDO migration:</strong>
            <a href="https://www.sitepoint.com/migrate-from-the-mysql-extension-to-pdo/" target="_blank">SitePoint</a>
        </li>
        <li><strong>Session & PHP logic references:</strong>
            <a href="https://stackoverflow.com/questions/20443607/php-if-to-display-html-based-on-session-isset" target="_blank">StackOverflow</a>
        </li>
        <li><strong>HTML & CSS help:</strong>
            <a href="https://www.w3schools.com/" target="_blank">W3Schools</a> — used for layout structure, form styling, navigation inspiration, and general front-end design
        </li>
        <li>
            <strong>Shell scripting guide:</strong> <a href="https://quickref.me/awk.html" target="_blank">AWK quickref</a>
        </li>
        <li><strong>Favicon:</strong>
            <a href="https://www.flaticon.com/free-icons/teleport" target="_blank">Teleport icon by Mihimihi on Flaticon</a>
        </li>
        <li>
            <strong>Font:</strong> <a href="https://fonts.google.com/specimen/Poppins" target="_blank">Poppins via Google Fonts</a>
        </li>
        <li><strong>Hero Banner Image:</strong> Generated using ChatGPT's image generation with some version of the prompt: <em>"Create a hero banner with a protein spiral to the left, padding and a clean color background."</em></li>
    </ul>

    <hr>

    <h2>Bioinformatics Tools & File Formats</h2>
    <ul>
        <li><strong>Clustal Omega:</strong> used to perform multiple sequence alignment, guide tree generation, and identity matrix plots.
            <ul>
                <li><a href="http://www.clustal.org/omega/README" target="_blank">Official README documentation</a></li>
                <li><a href="https://onlinelibrary.wiley.com/doi/full/10.1002/pro.3290" target="_blank">Sievers et al. (2018) paper</a></li>
            </ul>
        </li>
        <li><strong>EMBOSS patmatmotifs:</strong> used to detect motifs in protein sequences
            <ul>
                <li><a href="https://emboss.bioinformatics.nl/cgi-bin/emboss/help/patmatmotifs" target="_blank">Official EMBOSS patmatmotifs help</a></li>
                <li><a href="https://emboss.sourceforge.net/docs/themes/SequenceFormats.html" target="_blank">Supported sequence file formats</a></li>
            </ul>
        </li>
        <li><strong>jsPhyloSVG:</strong> Used to visualise guide trees interactively in-browser.
            <ul>
                <li>Library by <a href="https://github.com/guyleonard/jsPhyloSVG" target="_blank">Samuel Smits</a>, distributed under the <strong>GPL License</strong></li>
                <li>As per the license, appropriate credit has been given and the original citation is included below:</li>
                <li>Smits SA, Ouverney CC, 2010. <em>jsPhyloSVG: A Javascript Library for Visualizing Interactive and Vector-Based Phylogenetic Trees on the Web</em>. PLoS ONE 5(8): e12267. <a href="https://doi.org/10.1371/journal.pone.0012267" target="_blank">https://doi.org/10.1371/journal.pone.0012267</a></li>
                <li>Original license and source available on <a href="https://github.com/guyleonard/jsPhyloSVG/blob/master/jsphylosvg-min.js" target="_blank">GitHub</a></li>
            </ul>
        </li>
        <li><strong>Raphael.js:</strong> Visualisation dependency for jsPhyloSVG, loaded via CDN.</li>
        <li><strong>File Format References:</strong>
            <ul>
                <li><a href="https://en.wikipedia.org/wiki/Newick_format" target="_blank">Newick tree format</a></li>
                <li><a href="https://www.ebi.ac.uk/seqdb/confluence/display/JDSAT/File+Formats" target="_blank">EBI File Format Guide</a></li>
                <li><a href="https://www.phylo.org/index.php/help/clustal" target="_blank">CLUSTAL format description</a></li>
            </ul>
        </li>
    </ul>

    <hr>

    <h2>🤖 Use of AI Tools (ChatGPT)</h2>
    <p>ChatGPT was used extensively as a learning and debugging assistant. It was especially helpful when:</p>
    <ul>
        <li>Translating code from pymysql to PHP's PDO, after realising that Python-based SQL interaction was not within the accepted scope of the assessment.</li>
        <li>Writing bash scripts to loop over sequences and run patmatmotifs individually</li>
        <li>Cleaning up form validation, PHP display logic, and layout edge cases</li>
        <li>Understanding and integrating jsPhyloSVG for tree rendering</li>
        <li>Creating Python scripts to generate image plots (motif bar chart, identity matrix, etc.)</li>
        <li>Writing regular expressions and parsing outputs from EMBOSS and Biopython</li>
        <li>Suggesting content and layout improvements (e.g. how to use `ob_start()`, sticky footer, loading states — although didn't end up using that feature)</li>
        <li>Explaining unfamiliar parts of PHP or bash when I got stuck</li>
    </ul>
    <p>All suggestions were tested, reviewed, and mostly rewritten to match the structure of my project.</p>

    <hr>

    <h2>Original Work</h2>
    <ul>
        <li>Database structure (tables and relationships), designed based on course material</li>
        <li>SQL logic for inserting and retrieving data</li>
        <li>Layout and interface structure: forms, results pages, error handling, user views</li>
        <li>Styling written using custom CSS</li>
        <li>Also wrote the logic for saving results into a database, showing previous queries, and handling demo vs user searches</li>
    </ul>

    <hr>

    <h2>📁 Project GitHub</h2>
    <p>You can view the full codebase here: <a href="https://github.com/B272229-2024/ICA_IWDD" target="_blank">GitHub Repository</a></p>

    <hr>

    <p>Thanks for reading! This site was built with lots of learning, assisted with the tools listed above and help from ChatGPT 💡</p>
    </div>
_HTML;

$pageContent = ob_get_clean();
include './features/base_layout.php';
?>