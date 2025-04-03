#!/usr/bin/bash

INPUT=$1
RUN_ID=$2
OUT_DIR="scripts/output/$RUN_ID"
mkdir -p "$OUT_DIR"

TMP_MOTIF_OUT="$OUT_DIR/tmp_motifs.out"
MOTIF_TSV="$OUT_DIR/motif_results.tsv"
> "$MOTIF_TSV"  # Clear output file

# Split each FASTA sequence with null-terminated records
awk 'BEGIN{RS=">"; ORS=""} NR>1 {print ">"$0 "\0"}' "$INPUT" | \
while IFS= read -r -d '' fasta_entry; do
    # Skip empty
    [[ -z "$fasta_entry" ]] && continue

    # Extract RefSeq ID (first word of header)
    refseq_id=$(echo "$fasta_entry" | head -n1 | cut -d' ' -f1 | sed 's/^>//')

    echo "🔍 Running patmatmotifs on $refseq_id"

    # Run patmatmotifs on the single sequence
    echo "$fasta_entry" | patmatmotifs -sequence stdin -full Y -prune Y -outfile "$TMP_MOTIF_OUT" -auto

    # Extract motifs from output to TSV
    python3 scripts/extract_motifs.py "$TMP_MOTIF_OUT" "$MOTIF_TSV" "$refseq_id"
done

echo "✔ All motif results written to $MOTIF_TSV"
