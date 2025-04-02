#!/usr/bin/bash

echo "Running full clustalo analysis..."

INPUT=$1
ALIGNMENT_OUT=$2 # Align sequences with clustao
DISTMAT_OUT=$3 # Numerical measure of how similar each sequence is to the others
TREE_OUT=$4 # Guide tree output, to show relationship among sequences (similar to phylogenetic tree)

# Run the clustalo commands
clustalo -i "$INPUT" -o "$ALIGNMENT_OUT" --outfmt=clu --force

clustalo -i "$INPUT" -o /dev/null --distmat-out="$DISTMAT_OUT" --percent-id --full --force

clustalo -i "$INPUT" -o /dev/null --guidetree-out="$TREE_OUT" --force