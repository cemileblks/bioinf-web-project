#!/usr/bin/python3
import sys
import matplotlib.pyplot as plt
import numpy as np
import os
import pymysql
from config import DB_USER, DB_PASS, DB_NAME


if len(sys.argv) != 2:
    print("Usage: python3 plot_identity_matrix.py <run_id>")
    sys.exit(1)

run_id = sys.argv[1]

# Connect to DB
con = pymysql.connect(host="127.0.0.1", user=DB_USER, passwd=DB_PASS, db=DB_NAME)
cur = con.cursor()

# Fetch protein and taxon from Queries table
cur.execute("SELECT protein_family, taxon FROM Queries WHERE search_id = %s", (run_id,))
result = cur.fetchone()
protein_family, taxon = result if result else ("Unknown", "Unknown")

# Count number of sequences
cur.execute("SELECT COUNT(*) FROM Sequences WHERE search_id = %s", (run_id,))
num_sequences = cur.fetchone()[0]

cur.close()
con.close()

input_file = f"scripts/output/{run_id}/identity_matrix.txt"
output_img = f"scripts/output/{run_id}/identity_matrix.png"

# Read the matrix file
with open(input_file) as f:
    lines = f.readlines()

num = int(lines[0].strip())
labels = []
matrix = []

for line in lines[1:]:
    parts = line.strip().split()
    labels.append(parts[0])
    matrix.append([float(x) for x in parts[1:]])

matrix = np.array(matrix)

# Plot with matplotlib
fig, ax = plt.subplots(figsize=(8, 6))
cax = ax.matshow(matrix, cmap='Blues')

# Add labels
ax.set_xticks(range(num))
ax.set_yticks(range(num))
ax.set_xticklabels(labels, rotation=45, ha='left', fontsize=8)
ax.set_yticklabels(labels, fontsize=8)

# Annotate cells
for i in range(num):
    for j in range(num):
        ax.text(j, i, f"{matrix[i, j]:.1f}", va='center', ha='center', fontsize=7)

# Add colorbar
fig.colorbar(cax, label="% Identity")

plt.title(f"Sequence Identity Matrix for {num_sequences} sequences of '{protein_family}' from {taxon}", pad=20)
plt.tight_layout()
plt.savefig(output_img)
print(f"Identity matrix plot saved: {output_img}")
