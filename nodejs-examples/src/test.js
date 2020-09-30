var mysql      = require('mysql');
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'zemian',
  password : 'test123',
  database : 'testdb'
});
connection.connect();

function select(connection) {
	connection.query('SELECT * FROM test', function (error, results) {
	  if (error) throw error;
	  results.forEach(row => console.log(row));
	});	
}

function selectByCat(connection, cat) {
	connection.query('SELECT * FROM test WHERE cat = ?', [cat], function (error, results) {
	  if (error) throw error;
	  results.forEach(row => console.log(row));
	});
}

function insert(connection, cat, price, qty) {
	connection.query('insert into test(cat, price, qty) values (?, ?, ?)', [cat, price, qty], function (error, results) {
	  if (error) throw error;
	  console.log("Insert result=", results);
	});
}

function update(connection, id, qty) {
	connection.query('update test set qty = ? where id=?', [qty, id], function (error, results) {
	  if (error) throw error;
	  console.log("Update result=", results);
	});
}

function deleteById(connection, id) {
	connection.query('delete from test where id = ?', [id], function (error, results) {
	  if (error) throw error;
	  console.log("Delete result=", results);
	});
}

try {
	// Run example
	//select(connection);
	//insert(connection, 'nodejs', 90, 1);
	//insert(connection, 'nodejs', 90, 2);
	// update(connection, 13, 10);
	selectByCat(connection, 'nodejs');
	//deleteById(connection, 13);
} finally {
	connection.end();	
}
