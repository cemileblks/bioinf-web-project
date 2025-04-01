#!/usr/bin/bash

INPUT_FASTA=$1
IDENTIFIED_MOTIFS_FILE=$2

echo "Running patmatmotifs on $INPUT_FASTA..."
patmatmotifs -sequence "$INPUT_FASTA" -full Y -prune N -outfile "$IDENTIFIED_MOTIFS_FILE" -auto

