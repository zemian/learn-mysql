<?php

// A simple DB connection test
$host = "localhost";
$username = "zemian";
$password = "test123";
$dbname = "testdb";
$conn = new mysqli($host, $username, $password, $dbname);

try {
    echo "Connection successful! Connection object=" . $conn->stat() . "\n";
} finally {
    $conn->close();
}
