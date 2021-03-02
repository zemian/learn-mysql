import mysql.connector
conn = mysql.connector.connect(user='root', password='', host='localhost', database='testdb')

try:
	print("Connection successful!", conn)
finally:
	conn.close()
