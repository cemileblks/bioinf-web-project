#!/usr/bin/python3
import sys, os
from Bio import Entrez, SeqIO
from io import StringIO
# https://www.w3schools.com/python/python_modules.asp
# used chatgpt to debug module not loading
from config import ENTREZ_EMAIL, ENTREZ_API_KEY # config python file that stores env variables
from pop_queries import insert_query
from pop_sequences import insert_sequence

Entrez.email = ENTREZ_EMAIL
Entrez.api_key = ENTREZ_API_KEY

# Entrez (efetch) → FASTA string → Parse records → Filter by length → 
# → Save only those which meet filter → Populate database tables → Use that .fasta for alignment

# Function to retrieve sequence info based on user parameters with defaults
def get_sequences(protein, taxonomic_group, search_limit=10, min_len=0, max_len=100000):
    """Fetch sequences from NCBI based on protein and taxonomic group, and length of sequence"""

    query = f'{protein} AND "{taxonomic_group}"[Organism]' # Query search
    print(f"Searching NCBI with:\n{query}\n")

    # Overfetch to allow filtering later (so user can still get 20 even if the first search results don't math their critera)
    retmax = search_limit * 5
    
    # Perform Entrez search
    handle = Entrez.esearch(db="protein", term=query, retmax=retmax)
    record = Entrez.read(handle)
    
    print(record) # testing

    # Retrive the IDs of the resutls that got returned
    ids = record["IdList"]

    # If there are no sequences found:
    if len(ids) == 0:
        print("No sequences were found for your qurery!")
        return
    
    print(f"Found {len(ids)} IDs from NCBI (requesting up to {retmax})") # testing

    # Retrieve the fasta info for all the proteins found
    handle = Entrez.efetch(db="protein", id=ids, rettype="fasta", retmode="text")

    # read the fasta sequences as string
    fasta_data = handle.read()

    # Parse sequences and filter sequence length (https://biopython.org/wiki/SeqIO)
    records = list(SeqIO.parse(StringIO(fasta_data), "fasta"))
    print(f"\nDownloaded {len(records)} total sequences.")
    filtered_sequences = [rec for rec in records if min_len <= len(rec.seq) <= max_len]
    print("Found %i sequences that match filter" % len(filtered_sequences))

    # print(records)
    # print(fasta_data) # testing

    final_sequences = filtered_sequences[:search_limit]
    print(f"There are {len(final_sequences)} sequences asked for this search")
    # print(final_sequences)

    for record in final_sequences:
        print(f"{record.description}")
        print(f"{record.seq}")
        print(f"{record.id}")

    # create output file only if there are sequences found
    if len(final_sequences) > 0:

        # populate the database tables
        insert_query(run_id, protein, taxonomic_group, min_len, max_len, len(final_sequences))

        # fasta_records_db_id = []

        for record in final_sequences:
            seq_id = insert_sequence(run_id, record)
            record.id = f"seq_{seq_id}"
            # fasta_records_db_id.append(record)
        
        # print(fasta_records_db_id)

        # Full file paths based on script location (used chatgpt for this)
        script_dir = os.path.dirname(os.path.abspath(__file__))
        output_dir = os.path.join(script_dir, "output", sys.argv[6])

        print(f"created output directiory:{output_dir}")
        # Make directory for output file
        os.makedirs(output_dir, exist_ok=True)

        # Save to file
        filename = f"{output_dir}/sequences.fasta"
        # with open(filename, "w") as f:
        try:
            SeqIO.write(final_sequences, filename, "fasta")
            print(f"Successfully saved FASTA to {filename}")
        except Exception as e:
            print(f"ERROR: Could not save file: {e}")

        # Also print as proper FASTA to console
        print(f"\nFASTA output:\n")
        SeqIO.write(final_sequences, sys.stdout, "fasta")


# Makes sure the required arguments are suppied and gives usage instructions
if len(sys.argv) < 3:
    print("Usage: get_sequences.py protein_name taxonomic_group [search_limit] [min_len] [max_len]")
    sys.exit(-1)

# Assign arguments and the optional args
protein = sys.argv[1]
taxon = sys.argv[2]
limit = int(sys.argv[3])
min_len = int(sys.argv[4])
max_len = int(sys.argv[5])
run_id = sys.argv[6]

print(f'<br>I AM PYTHON FILE BEING RUN WITH THE RUN_ID:{run_id}<br>')

get_sequences(protein, taxon, limit, min_len, max_len)

