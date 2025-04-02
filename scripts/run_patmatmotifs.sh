#!/usr/bin/bash

# https://quickref.me/awk.html

INPUT=$1
RUN_ID=$2

# Temporary file for motif output (reused for each sequence)
TMP_MOTIF_FILE="$(pwd)/scripts/output/$RUN_ID/tmp_motifs.out"
echo $TMP_MOTIF_FILE

# Use awk to split entries with null terminator
awk 'BEGIN{RS=">"; ORS=""} NR>1 {print ">"$0 "\0"}' "$INPUT" | \
while IFS= read -r -d '' fasta_entry; do
    # Skip empty
    [[ -z "$fasta_entry" ]] && continue

    # Extract internal db sequence ID (just the number after seq_) from header
    internal_sequence_id=$(echo "$fasta_entry" | head -n1 | grep -o 'seq_[0-9]\+' | sed 's/seq_//')

    echo "Running patmatmotifs on $internal_sequence_id..."

    # Run patmatmotifs, feeding sequence via a process substitution
    echo "$fasta_entry" | patmatmotifs -sequence stdin -full Y -prune Y -outfile "$TMP_MOTIF_FILE" -auto

    # Immediately call the Python script to populate the DB
    python3 "$(pwd)/scripts/pop_motifs.py" "$RUN_ID" "$TMP_MOTIF_FILE" "$internal_sequence_id"

    echo "Finished processing $internal_sequence_id"
    echo
done