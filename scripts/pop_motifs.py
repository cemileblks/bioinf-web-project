# #!/usr/bin/python3
import pymysql
import sys, re
from config import DB_USER, DB_PASS, DB_NAME
# https://builtin.com/software-engineering-perspectives/define-empty-variables-python
# https://www.w3schools.com/python/python_regex.asp

# Check for correct usage
if len(sys.argv) != 3:
    print("Usage: python3 pop_motifs.py <run_id> <motif_file>")
    sys.exit(1)

search_id = sys.argv[1]
motif_file = sys.argv[2]

# start connection to DB
con = pymysql.connect(host='127.0.0.1', user=DB_USER, passwd=DB_PASS, db=DB_NAME)
cur = con.cursor()

# Function that inserts a single motif entry into the DB
def insert_motif(search_id, sequence_id, motif_name, start, end):
    prosite_id = motif_name  # Extract real ID later (or delete)

    sql = "INSERT INTO Motifs (search_id, sequence_id, prosite_id, motif_name, start_pos, end_pos) VALUES (%s, %s, %s, %s, %s, %s)"
    cur.execute(sql, (search_id, sequence_id, prosite_id, motif_name, start, end))
    print(f"Inserted motif {motif_name} for {current_refseq_id} ({start}-{end})")

# Initalise variables
current_refseq_id = None
sequence_id = None
motif_name = None
start_pos = None
end_pos = None

# Open motif result file and loop through each line
with open(motif_file, "r") as mt:
    for line in mt:
        line = line.rstrip()
        
        # Identify the RefSeq ID (e.g. XP_072197995.1) of the motif search
        if line.startswith("# Sequence"):
            seq_info = line.split()
            current_refseq_id = seq_info[2]

            # Try to get matching sequence_id from Sequences table
            cur.execute(
                "SELECT id FROM Sequences WHERE refseq_id = %s AND search_id = %s",
                (current_refseq_id, search_id)
            )
            result = cur.fetchone()
            if result:
                sequence_id = result[0]
                print(f"Found sequence {current_refseq_id} with ID {sequence_id}")
            else:
                print(f"Sequence '{current_refseq_id}' with search_id '{search_id}' not found in DB — skipping.")
                sequence_id = None

        # Regex search for "Start = position X of sequence"
        elif line.startswith("Start"):
            start_pos = int(re.search(r'position (\d+)', line).group(1))

        # Regex search for "End = position X of sequence"
        elif line.startswith("End"):
            end_pos = int(re.search(r'position (\d+)', line).group(1))

        # Search for "Motif = ..." to extract motif name
        elif line.startswith("Motif"):
            motif_name = line.split("=")[1].strip()

        if sequence_id and motif_name and start_pos is not None and end_pos is not None:
            insert_motif(search_id, sequence_id, motif_name, start_pos, end_pos)
            motif_name = None
            start_pos = None
            end_pos = None

con.commit()
cur.close()
con.close()