import mysql.connector
conn = mysql.connector.connect(user='zemian', password='test123', host='localhost', database='testdb')

try:
	print("Connection successful!", conn)
finally:
	conn.close()
