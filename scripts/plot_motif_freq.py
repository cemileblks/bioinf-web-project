# #!/usr/bin/python3

import pymysql
import pandas as pd
import matplotlib.pyplot as plt
import sys
import os
from config import DB_USER, DB_PASS, DB_NAME

# Get run_id from command line
if len(sys.argv) != 2:
    print("Usage: python3 plot_motif_freq.py <run_id>")
    sys.exit(1)

run_id = sys.argv[1]

# Connect to DB
con = pymysql.connect(host='127.0.0.1', user=DB_USER, passwd=DB_PASS, db=DB_NAME)
cur = con.cursor()

# Get protein family and taxon from Queries
cur.execute("""
    SELECT protein_family, taxon FROM Queries WHERE search_id = %s
""", (run_id,))
row = cur.fetchone()

if row:
    protein_family, taxon = row
else:
    protein_family, taxon = "Unknown Protein", "Unknown Taxon"

# Get motif data
query = """
    SELECT motif_name
    FROM Motifs
    WHERE search_id = %s
"""
df = pd.read_sql(query, con, params=[run_id])
con.close()

if df.empty:
    print("No motifs found for this search.")
    sys.exit(0)

# Group and count motifs
motif_counts = df['motif_name'].value_counts().reset_index()
motif_counts.columns = ['motif_name', 'count']

# Plot using matplotlib
plt.figure(figsize=(10, 6))
bars = plt.bar(motif_counts['motif_name'], motif_counts['count'], color='#7D5BA6')  # pretty purple

plt.xlabel("Motif")
plt.ylabel("Count")
plt.title(f"Motif Frequency for {protein_family} in {taxon}", fontsize=14, weight='bold')
plt.xticks(rotation=45, ha='right')
plt.tight_layout()

# Save plot
output_dir = f"scripts/output/{run_id}"
os.makedirs(output_dir, exist_ok=True)
output_path = f"{output_dir}/motif_frequency.png"
plt.savefig(output_path)
print(f"Saved plot to {output_path}")
