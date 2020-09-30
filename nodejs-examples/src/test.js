// Example to test simple CRUD operations
var mysql = require('mysql');
var conn = mysql.createConnection({
  host     : 'localhost',
  user     : 'zemian',
  password : 'test123',
  database : 'testdb'
});
conn.connect();

function select(conn) {
	conn.query('SELECT * FROM test', function (error, results) {
	  if (error) throw error;
	  results.forEach(row => console.log(row));
	});	
}

function selectByCat(conn, cat) {
	conn.query('SELECT * FROM test WHERE cat = ?', [cat], function (error, results) {
	  if (error) throw error;
	  results.forEach(row => console.log(row));
	});
}

function getTotal(conn, cat) {
	conn.query('SELECT sum(price) AS total FROM test WHERE cat = ?', [cat], function (error, results) {
	  if (error) throw error;
	  ret = results[0].total;
	  // Notice that we can't return ret due to JS callback unless we do await/async
	  console.log("Total: ", ret);
	});
}

function insert(conn, cat, price, qty) {
	conn.query('INSERT INTO test(cat, price, qty) VALUES (?, ?, ?)', [cat, price, qty], function (error, results) {
	  if (error) throw error;
	  console.log("Insert result=", results);
	});
}

function update(conn, id, price, qty) {
	conn.query('UPDATE test SET price = ?, qty = ? WHERE id = ?', [price, qty, id], function (error, results) {
	  if (error) throw error;
	  console.log("Update result=", results);
	});
}

function deleteById(conn, id) {
	conn.query('DELETE FROM test WHERE id = ?', [id], function (error, results) {
	  if (error) throw error;
	  console.log("Delete result=", results);
	});
}

try {
	// Run example
	//select(conn);
	//selectByCat(conn, 'test');
	//insert(conn, 'nodejs', 0.10, 1);
	//insert(conn, 'nodejs', 0.20, 2);
	//getTotal(conn, 'nodejs');
	//update(conn, 13, 0.99, 10);
	//deleteById(conn, 13);
	selectByCat(conn, 'nodejs');	
} finally {
	conn.end();	
}
