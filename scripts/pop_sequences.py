#!/usr/bin/python3
# Python script to populate sequences table in the database
import pymysql
from config import DB_USER, DB_PASS, DB_NAME

def insert_sequence(query_id, record):

    con = pymysql.connect(host='127.0.0.1', user=DB_USER, passwd=DB_PASS, db=DB_NAME)

    cur = con.cursor()

    refseq_id = record.id
    sequence = str(record.seq)
    # Extract species from the FASTA description line
    species = record.description.split("[")[-1].rstrip("]") if "[" in record.description else "Unknown"

    sql = "INSERT INTO Sequences (refseq_id, search_id, species, sequence) VALUES (%s, %s, %s, %s)"

    cur.execute(sql, (refseq_id, query_id, species, sequence))
    con.commit()
    cur.close()
    con.close()