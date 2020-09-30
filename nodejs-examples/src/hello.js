// A simple DB connection test 
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
	conn.end();
}
 
