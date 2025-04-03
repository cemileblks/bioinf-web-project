#!/usr/bin/python3

import sys
import pandas as pd
import matplotlib.pyplot as plt
import os

# Usage check
if len(sys.argv) != 5:
    print("Usage: python3 plot_motif_freq_from_csv.py <run_id> <csv_file> <protein_family> <taxon>")
    sys.exit(1)

run_id = sys.argv[1]
csv_file = sys.argv[2]
protein_family = sys.argv[3]
taxon = sys.argv[4]

# Load CSV
df = pd.read_csv(csv_file)
if df.empty:
    print("No motif data found or invalid format.")
    sys.exit(0)

# Plot
plt.figure(figsize=(10, 6))
bars = plt.bar(df['motif_name'], df['count'], color='#7D5BA6')  # pretty purple

plt.xlabel("Motif")
plt.ylabel("Count")
plt.title(f"Motif Frequency for {protein_family} in {taxon}", fontsize=14, weight='bold')
plt.xticks(rotation=45, ha='right')

# Force y-axis ticks as integers
plt.yticks(range(0, max(df['count']) + 2))

plt.tight_layout()

# Save
output_dir = f"scripts/output/{run_id}"
os.makedirs(output_dir, exist_ok=True)
output_path = f"{output_dir}/motif_frequency.png"
plt.savefig(output_path)
print(f"Saved plot to {output_path}")
