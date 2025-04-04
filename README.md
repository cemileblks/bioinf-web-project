# 🌀 Protein Swirl

**Protein Swirl** is a website to search, analyse, and visualise protein sequences by taxon and family. Built for the *Introduction to Website and Database Design (BILG11016)* course at the University of Edinburgh, it combines sequence retrieval, multiple sequence alignment, motif detection, and visualisation — all wrapped in a simple user interface.

## Features

- Search proteins by name and taxonomic group
- Filter by sequence length and number of results
- Run **Clustal Omega** for multiple sequence alignment
- Visualise a guide tree using **jsPhyloSVG**
- Detect conserved motifs with **EMBOSS patmatmotifs**
- Generate visual plots (identity matrix, motif frequency)
- Save and revisit your queries if logged in
- Try it without login via demo mode

## Technologies Used

- PHP with PDO for database interaction
- Python for sequence processing & plotting
- Bash scripting for backend automation
- MySQL for persistent storage
- Biopython, Clustal Omega, EMBOSS tools
- jsPhyloSVG + Raphael.js for visualising trees

## Demo

Try the live version here:  
🌐 [https://bioinfmsc8.bio.ed.ac.uk/~s2756532/web_project/index.php](https://bioinfmsc8.bio.ed.ac.uk/~s2756532/web_project/index.php)

## Statement of Credits

All tools, libraries, and AI usage have been documented in detail on the site's  
📚 [Statement of Credits](https://bioinfmsc8.bio.ed.ac.uk/~s2756532/web_project/credits.php)

## 🧾 License

This project was developed for educational purposes only.