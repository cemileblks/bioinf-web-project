#!/usr/bin/python3
import sys, os, csv
from Bio import Entrez, SeqIO
from io import StringIO
from config import ENTREZ_EMAIL, ENTREZ_API_KEY

Entrez.email = ENTREZ_EMAIL
Entrez.api_key = ENTREZ_API_KEY

# Entrez (efetch) → FASTA string → Parse records → Filter by length →
# → Save to .csv and .fasta for downstream use

def get_sequences(protein, taxonomic_group, search_limit=10, min_len=0, max_len=100000):
    """Fetch sequences from NCBI based on protein and taxonomic group, then filter and export"""

    query = f'{protein} AND "{taxonomic_group}"[Organism]'
    print(f"Searching NCBI with:\n{query}\n")

    retmax = search_limit * 5
    handle = Entrez.esearch(db="protein", term=query, retmax=retmax)
    record = Entrez.read(handle)
    ids = record["IdList"]

    if not ids:
        print("No sequences found.")
        return

    print(f"Found {len(ids)} IDs from NCBI (requesting up to {retmax})")

    handle = Entrez.efetch(db="protein", id=ids, rettype="fasta", retmode="text")
    fasta_data = handle.read()

    records = list(SeqIO.parse(StringIO(fasta_data), "fasta"))
    print(f"Downloaded {len(records)} total sequences.")

    filtered = [r for r in records if min_len <= len(r.seq) <= max_len]
    final = filtered[:search_limit]
    print(f"Filtered to {len(final)} sequences.")

    if not final:
        print("No sequences passed filter.")
        return

    # Output directory
    script_dir = os.path.dirname(os.path.abspath(__file__))
    output_dir = os.path.join(script_dir, "output", run_id)
    os.makedirs(output_dir, exist_ok=True)

    # Save FASTA
    fasta_file = os.path.join(output_dir, "sequences.fasta")
    SeqIO.write(final, fasta_file, "fasta")
    print(f"Saved FASTA: {fasta_file}")

    # Save CSV
    csv_file = os.path.join(output_dir, "sequences.csv")
    with open(csv_file, "w", newline='') as csvfile:
        writer = csv.writer(csvfile)
        writer.writerow(["refseq_id", "species", "sequence", "description"])
        for rec in final:
            species = rec.description.split("[")[-1].rstrip("]") if "[" in rec.description else "Unknown"
            writer.writerow([rec.id, species, str(rec.seq), rec.description])
    print(f"Saved CSV: {csv_file}")

# === Main script logic ===
if len(sys.argv) < 7:
    print("Usage: get_sequences.py <protein> <taxon> <limit> <min_len> <max_len> <run_id>")
    sys.exit(1)

protein = sys.argv[1]
taxon = sys.argv[2]
limit = int(sys.argv[3])
min_len = int(sys.argv[4])
max_len = int(sys.argv[5])
run_id = sys.argv[6]

get_sequences(protein, taxon, limit, min_len, max_len)
