// A simple DB connection test 
// https://github.com/mysqljs/mysql#readme
var mysql = require('mysql');
var conn = mysql.createConnection({
  host     : 'localhost',
  user     : 'zemian',
  password : 'test123',
  database : 'testdb'
}); 
conn.connect();

try {
	conn.query('SELECT 1 + 1 as result', function (error, results) {
	  if (error) throw error;
	  console.log('Connection successful! Test Result: ', results[0].result);
	});
} finally {
	// This only works because we are not using the connection pool, and 
	// it actually will wait for conn queries to complete before it ends it!
	// TODO: A better way is to wrap it in Promise object.
	conn.end();
}
 
