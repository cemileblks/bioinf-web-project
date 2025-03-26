#!/usr/bin/python3
import sys, os
from Bio import Entrez

Entrez.email = os.getenv("ENTREZ_EMAIL")
Entrez.api_key = os.getenv("ENTREZ_API_KEY")

def get_sequences(protein, taxonomic_group, search_limit=10):
    """Fetch sequences from NCBI based on protein and taxonomic group."""
    query = f'{protein} AND "{taxonomic_group}"[Organism]'
    
    # Perform Entrez search
    handle = Entrez.esearch(db="protein", term=query, retmax=search_limit)
    record = Entrez.read(handle)
    
    print(record) # testing

    ids = record["IdList"]
    if len(ids) == 0:
        print("No sequences were found!")
        return

    # Retrieve the fasta info for the proteins found
    handle = Entrez.efetch(db="protein", id=ids, rettype="fasta", retmode="text")

    # read the fasta sequences as string
    fasta_data = handle.read()
    
    print(fasta_data) # testing
    
    # # Make directory for output file
    # os.makedirs("output", exist_ok=True)

    # filename = f"output/query_{sys.argv[4]}.fasta"
    # with open(filename, "w") as f:
    #     f.write(fasta_data)

    print(f"Fetched {len(ids)} sequences.")
    # print(f"Saved to {filename}")


# Makes sure the required arguments are suppied
if len(sys.argv) < 3:
    print("Usage: fetch_sequences protein_name taxonomic_group [search_limit]")
    sys.exit(-1)

# Assign arguments and the optional limit arg
protein = sys.argv[1]
taxon = sys.argv[2]
limit = int(sys.argv[3]) if len(sys.argv) > 3 else 10

get_sequences(protein, taxon, limit)

