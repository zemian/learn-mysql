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
conn.query('SELECT 1 + 1 as result', function (error, results) {
  if (error) throw error;
  console.log('Connection successful! Test Result: ', results[0].result);
});

//
// NOTE: 
// Despite this code is from their official doc, but notice we trying to end 
// the connection right after callback query. That's usually a bad practice. 
// But it is still working because it actually will wait for conn queries to 
// complete before it ends it!
// 
conn.end();
