#!/usr/bin/python3
import pymysql
import sys
import os
from config import DB_USER, DB_PASS, DB_NAME

if len(sys.argv) != 5:
    print("Usage: python3 pop_analyses.py <run_id> <type> <file_path> <label>")
    sys.exit(1)

run_id = sys.argv[1]
analysis_type = sys.argv[2]         # e.g. clustalo, motif, etc.
file_path = sys.argv[3]             # Full path or relative to web root
label = sys.argv[4]                 # e.g. 'Alignment file' or 'Motif Plot'

# Get file extension as type
file_type = os.path.splitext(file_path)[-1].lstrip('.')

# Connect to database
con = pymysql.connect(host='127.0.0.1', user=DB_USER, passwd=DB_PASS, db=DB_NAME)
cur = con.cursor()

sql = "INSERT INTO Analyses (search_id, type, result_path, label, file_type) VALUES (%s, %s, %s, %s, %s)"

cur.execute(sql, (run_id, analysis_type, file_path, label, file_type))
con.commit()

print(f"Added {analysis_type} analysis: {label}")
cur.close()
con.close()
