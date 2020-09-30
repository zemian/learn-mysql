import mysql.connector
conn = mysql.connector.connect(user='zemian', password='test123', host='localhost', database='testdb')

def select_all(conn):
	cursor = conn.cursor()
	cursor.execute('SELECT * FROM test')
	ret = cursor.fetchall()
	cursor.close()
	return ret

def select_by_id(conn, id):
	cursor = conn.cursor()
	cursor.execute('SELECT * FROM test WHERE id = %s', (id,))
	ret = cursor.fetchone()
	cursor.close()
	return ret

def select_by_cat(conn, cat):
	cursor = conn.cursor()
	cursor.execute('SELECT * FROM test WHERE cat = %s', (cat,))
	ret = cursor.fetchall()
	cursor.close()
	return ret

def select_total(conn, cat):
	cursor = conn.cursor()
	cursor.execute('SELECT sum(price) FROM test WHERE cat = %s', (cat,))
	ret = cursor.fetchone()[0]
	cursor.close()
	return ret

def insert(conn, cat, price, qty):
	ret = None
	cursor = conn.cursor()
	cursor.execute('INSERT INTO test(cat, price, qty) VALUES (%s, %s, %s)', (cat, price, qty))
	if (cursor.rowcount):
		ret = cursor.lastrowid
	cursor.close()
	conn.commit()
	return ret

def update(conn, id, price, qty):
	ret = None
	cursor = conn.cursor()
	cursor.execute('UPDATE test SET price = %s, qty = %s WHERE id = %s', (price, qty, id))
	if (cursor.rowcount):
		ret = id
	cursor.close()
	conn.commit()
	return ret

def delete(conn, id):
	ret = None
	cursor = conn.cursor()
	cursor.execute('DELETE FROM test WHERE id = %s', (id,))
	if (cursor.rowcount):
		ret = id
	cursor.close()
	conn.commit()
	return ret

def delete_by_cat(conn, cat):
	cursor = conn.cursor()
	cursor.execute('DELETE FROM test WHERE cat = %s', (cat,))
	ret = cursor.rowcount
	cursor.close()
	conn.commit()
	return ret

try:
	print(select_all(conn))
	# print(select_by_id(conn, 1))
	# print(select_by_cat(conn, 'test'))

	# print(insert(conn, 'py', 0.10, 1))
	# print(insert(conn, 'py', 0.20, 2))
	# print(select_by_cat(conn, 'py'))
	
	# print(select_total(conn, 'py'))
	# print(update(conn, 37, 0.99, 10))
	# print(select_total(conn, 'py'))
	
	# print(select_by_id(conn, 37))
	# print(delete(conn, 37))
	# print(select_by_id(conn, 37))

	# print(select_by_cat(conn, 'py'))
	# print(delete_by_cat(conn, 'py'))
	# print(select_by_cat(conn, 'py'))
finally:
	conn.close()
