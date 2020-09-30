import mysql.connector
conn = mysql.connector.connect(user='zemian', password='test123', host='localhost', database='testdb')

def select(conn):
	cursor = conn.cursor()
	cursor.execute('SELECT * FROM test')
	for row in cursor:
		print("{}".format(row))
	cursor.close()

def select_by_id(conn, id):
	cursor = conn.cursor()
	cursor.execute('SELECT * FROM test WHERE id = %s', (id,))
	ret = cursor.fetchone()
	cursor.close()
	print("Record: {}".format(ret))
	return ret

def select_by_cat(conn, cat):
	cursor = conn.cursor()
	cursor.execute('SELECT * FROM test WHERE cat = %s', (cat,))
	for row in cursor:
		print("{}".format(row))
	cursor.close()

def select_total(conn, cat):
	cursor = conn.cursor()
	cursor.execute('SELECT sum(price) FROM test WHERE cat = %s', (cat,))
	ret = cursor.fetchone()[0]
	cursor.close()
	return ret

def insert(conn, cat, price, qty):
	cursor = conn.cursor()
	cursor.execute('INSERT INTO test(cat, price, qty) VALUES (%s, %s, %s)', (cat, price, qty))
	id = cursor.lastrowid
	if (cursor.rowcount):
		print("Inserted ID={}".format(id))
	cursor.close()
	conn.commit()

def update(conn, id, price, qty):
	cursor = conn.cursor()
	cursor.execute('UPDATE test SET price = %s, qty = %s WHERE id = %s', (price, qty, id))
	if (cursor.rowcount):
		print("Updated ID={}".format(id))
	cursor.close()
	conn.commit()

def delete(conn, id):
	cursor = conn.cursor()
	cursor.execute('DELETE FROM test WHERE id = %s', (id,))
	if (cursor.rowcount):
		print("Deleted ID={}".format(id))
	cursor.close()
	conn.commit()

try:
	# select(conn)
	# select_by_cat(conn, 'test')
	#insert(conn, 'py', 0.10, 1)
	#insert(conn, 'py', 0.20, 2)
	#print("Total: {}".format(select_total(conn, 'py')))
	# update(conn, 23, 0.99, 10)
	#delete(conn, 23)
	#select_by_id(conn, 23)
	#insert(conn, 'py', 0.20, 2)
	select_by_cat(conn, 'py')
finally:
	conn.close()
