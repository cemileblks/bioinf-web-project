#!/usr/bin/python3
import sys
import matplotlib.pyplot as plt
import numpy as np
import os

if len(sys.argv) != 5:
    print("Usage: python3 plot_identity_matrix_simple.py <run_id> <protein_family> <taxon> <num_sequences>")
    sys.exit(1)

run_id = sys.argv[1]
protein_family = sys.argv[2]
taxon = sys.argv[3]
num_sequences = int(sys.argv[4])

input_file = f"scripts/output/{run_id}/identity_matrix.txt"
output_img = f"scripts/output/{run_id}/identity_matrix.png"

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

fig, ax = plt.subplots(figsize=(8, 6))
cax = ax.matshow(matrix, cmap='Blues')

ax.set_xticks(range(num))
ax.set_yticks(range(num))
ax.set_xticklabels(labels, rotation=45, ha='left', fontsize=8)
ax.set_yticklabels(labels, fontsize=8)

for i in range(num):
    for j in range(num):
        ax.text(j, i, f"{matrix[i, j]:.1f}", va='center', ha='center', fontsize=7)

fig.colorbar(cax, label="% Identity")

plt.title(f"Sequence Identity Matrix for {num_sequences} sequences of '{protein_family}' from {taxon}", pad=20)
plt.tight_layout()
plt.savefig(output_img)
print(f"Identity matrix plot saved: {output_img}")
