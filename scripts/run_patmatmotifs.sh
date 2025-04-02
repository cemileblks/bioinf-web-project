#!/usr/bin/bash

# https://quickref.me/awk.html

INPUT=$1
RUN_ID=$2

# Temporary file for motif output (reused for each sequence)
TMP_MOTIF_FILE="tmp_motifs.out"

# Use awk to split entries with null terminator
awk 'BEGIN{RS=">"; ORS=""} NR>1 {print ">"$0 "\0"}' "$INPUT" | \
while IFS= read -r -d '' fasta_entry; do
    # Skip empty
    [[ -z "$fasta_entry" ]] && continue

    # Extract RefSeq ID from header (assumes it's the first word after '>')
    refseq_id=$(echo "$fasta_entry" | head -n1 | cut -d' ' -f1 | sed 's/^>//')

    echo "Running patmatmotifs on $refseq_id..."

    # Run patmatmotifs, feeding sequence via a process substitution
    echo "$fasta_entry" | patmatmotifs -sequence stdin -full Y -prune N -outfile "$TMP_MOTIF_FILE" -auto

    # Immediately call the Python script to populate the DB
    python3 ../../pop_motifs.py "$RUN_ID" "$TMP_MOTIF_FILE"

    echo "Finished processing $refseq_id"
    echo
done