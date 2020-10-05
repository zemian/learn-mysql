// Example to test simple CRUD operations
let mysql = require('mysql');
let assert = require('assert');

function createConn() {
	let conn = mysql.createConnection({
	  host     : 'localhost',
	  user     : 'zemian',
	  password : 'test123',
	  database : 'testdb'
	});
	conn.connect();
	return conn;
}

function insert(conn, cat, price, qty) {
	return new Promise(resolve => {
		conn.query('INSERT INTO test(cat, price, qty) VALUES (?, ?, ?)', [cat, price, qty], function (error, result) {
		  if (error) throw error;
		  resolve(result.insertId)
		});
	});
}

// Use this to test whether record exists
function selectById(conn, id) {
	return new Promise(resolve => {
		conn.query('SELECT * FROM test WHERE id = ?', [id], function (error, result) {
		  if (error) throw error;
		  resolve(result);
		});	
	});
}

// Throws exception if record does not exists.
function getById(conn, id) {
	return new Promise(resolve => {
		conn.query('SELECT * FROM test WHERE id = ?', [id], function (error, result) {
		  if (error) throw error;
		  resolve(result[0]);
		});
	});
}

function selectByCat(conn, cat) {
	return new Promise(resolve => {
		conn.query('SELECT * FROM test WHERE cat = ?', [cat], function (error, result) {
		  if (error) throw error;
		  resolve(result);
		});
	});
}

function selectTotal(conn, cat) {
	return new Promise(resolve => {
		conn.query('SELECT sum(price) AS total FROM test WHERE cat = ?', [cat], function (error, result) {
		  if (error) throw error;
		  ret = result[0].total;
		  resolve(ret);
		});
	});
}

function update(conn, id, price, qty) {
	return new Promise(resolve => {
		conn.query('UPDATE test SET price = ?, qty = ? WHERE id = ?', [price, qty, id], function (error, result) {
		  if (error) throw error;
		  resolve(1);
		});
	});
}

function deleteById(conn, id) {
	return new Promise(resolve => {
		conn.query('DELETE FROM test WHERE id = ?', [id], function (error, result) {
		  if (error) throw error;
		  resolve(1);
		});
	});
}

function deleteByCat(conn, cat) {
	// We need to do double query because MySQL can not get affected rows?
	return selectByCat(conn, cat).then(rows => {
		return new Promise(resolve => {
			conn.query('DELETE FROM test WHERE cat = ?', [cat], function (error, result) {
			  if (error) throw error;
			  resolve(rows.length);
			});
		});
	});
}

// Test functions
function isclose(a, b) {
	return Math.abs(a - b) < 0.00001;
}

// Main script test
(async () => {
	let conn = createConn();
	try {
		let testCat = Math.random().toString(16).substring(2, 12);
		let testCount = 25;
		let testIds = [];

		console.log("Test insert with " + testCount + " rows and cat" + testCat);	
		for (let i = 1; i <= testCount; i++) {
			await insert(conn, testCat, 0.10 + i, i).then((id) => {
				assert(id > 0);
				testIds.push(id);
			});
		}

		console.log("Test selectByCat");
		await selectByCat(conn, testCat).then((rows) => {
			assert(rows.length === testCount);
			assert(rows[0].qty === 1);
			assert(rows[1].qty === 2);
			assert(rows[0].price > 0.10);
			assert(rows[1].price > 0.10);
		});

		console.log("Test selectById");
		await selectById(conn, testIds[0]).then((rows) => {
			assert(rows.length === 1);
			let row = rows[0];
			assert(row.id === testIds[0]);
			assert(row.cat === testCat);
			assert(isclose(row.price, 1.10));
			assert(row.qty === 1);
		});

		console.log("Test getById");
		await getById(conn, testIds[0]).then((row) => {
			console.log("test selectById:", testIds[0]);
			assert(row.id === testIds[0]);
			assert(row.cat === testCat);
			assert(isclose(row.price, 1.10));
			assert(row.qty === 1);
		});

		console.log("Test selectTotal");
		await selectTotal(conn, testCat).then((total) => {
			assert(isclose(total, 327.5000));
		});

		console.log("Test conn reconnect");
		await conn.end();
		conn = createConn();

		console.log("Test update");
		await update(conn, testIds[0], 0.99, 998877).then(async (count) => {
			assert(count === 1);
			await getById(conn, testIds[0]).then((row) => {
				assert(row.id === testIds[0]);
				assert(row.cat === testCat);
				assert(isclose(row.price, 0.99));
				assert(row.qty === 998877);
			});
		});
		
		console.log("Test deleteById");
		await deleteById(conn, testIds[0]).then(async (count) => {
			assert(count === 1);
			await selectById(conn, testIds[0]).then((rows) => {
				assert(rows.length === 0);
			});
		});

		console.log("Test deleteByCat");
		await deleteByCat(conn, testCat).then((count) => {
			assert(count === testCount - 1);
		});
		await selectByCat(conn, testCat).then((rows) => {
			assert(rows.length === 0);
		});
	} finally {
		await conn.end();
	}
})();

