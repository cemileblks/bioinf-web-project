#!/usr/bin/bash

echo "Running plotcon..."

ALIGNMENT_INPUT=$1
OUTPUT=$2

plotcon -sequences "$ALIGNMENT_INPUT" -graph png -gtitle "Conservation plot" -goutfile "$OUTPUT" -auto