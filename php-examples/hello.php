<?php

// A simple DB connection test
echo "Connection Test: ";
$host = "localhost";
$username = "zemian";
$password = "test123";
$dbname = "testdb";
$conn = new mysqli($host, $username, $password, $dbname);
echo "Successful!\n";

echo "Query Test:\n";
$rows = $conn->query('SELECT 1 + 1');
var_dump($rows);

$conn->close();
