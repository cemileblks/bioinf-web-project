#!/usr/bin/python3
import sys, re, csv

# Arguments: motif_out_file, tsv_out_file, refseq_id
if len(sys.argv) != 4:
    print("Usage: python3 extract_motifs.py <motif_output_file> <tsv_output_file> <refseq_id>")
    sys.exit(1)

motif_file = sys.argv[1]
tsv_output = sys.argv[2]
refseq_id = sys.argv[3]

motifs = []
motif_name = None
start = None
end = None

with open(motif_file, "r") as f:
    for line in f:
        line = line.strip()

        if line.startswith("Motif"):
            motif_name = line.split("=", 1)[1].strip()

        elif line.startswith("Start"):
            match = re.search(r'position (\d+)', line)
            if match:
                start = int(match.group(1))

        elif line.startswith("End"):
            match = re.search(r'position (\d+)', line)
            if match:
                end = int(match.group(1))

        # When all fields are ready, save motif row
        if motif_name and start is not None and end is not None:
            motifs.append([refseq_id, motif_name, motif_name, start, end])
            motif_name = start = end = None

# Append to file
with open(tsv_output, "a", newline='') as out_f:
    writer = csv.writer(out_f, delimiter="\t")
    for row in motifs:
        writer.writerow(row)

print(f"✔ Extracted {len(motifs)} motifs to {tsv_output}")
