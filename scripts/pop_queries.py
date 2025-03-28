#!/usr/bin/python3
# Python script to populate sequences table in the database
import pymysql
from config import DB_USER, DB_PASS, DB_NAME


def insert_query(query_id, protein_family, taxon, min_len, max_len, limit):

  con = pymysql.connect(host='127.0.0.1', user=DB_USER, passwd=DB_PASS, db=DB_NAME)

  cur = con.cursor()

  sql = "INSERT INTO Queries (search_id, protein_family, taxon, min_len, max_len, no_of_sequences) VALUES (%s, %s, %s, %s, %s, %s)"
  cur.execute(sql, (query_id, protein_family, taxon, min_len, max_len, limit))
  con.commit()
  cur.close()
  con.close()