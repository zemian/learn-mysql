<?php

// Example to test simple CRUD operations
$host = "localhost";
$username = "zemian";
$password = "test123";
$dbname = "testdb";
$conn = new mysqli($host, $username, $password, $dbname);

function select($conn) {
	$result = $conn->query('SELECT * FROM test');
	while($row = $result->fetch_assoc()) {
	 	var_dump($row);
	}
	$result->close();
}

function select_by_cat($conn, $cat) {
	$stmt = $conn->prepare('SELECT * FROM test WHERE cat = ?');
	$stmt->bind_param('s', $cat);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
	 	var_dump($row);
	}
	$result->close();
	$stmt->close();
}

function select_total($conn, $cat) {
	$ret = 0;
	$stmt = $conn->prepare('SELECT sum(price) AS total FROM test WHERE cat = ?');
	$stmt->bind_param('s', $cat);
	$stmt->execute();
	$result = $stmt->get_result();
	$ret = $result->fetch_row()[0];
	$result->close();
	$stmt->close();

	return $ret;
}

function insert($conn, $cat, $price, $qty) {
	$stmt = $conn->prepare('INSERT INTO test(cat, price, qty) VALUES (?, ?, ?)');
	$stmt->bind_param('sdi', $cat, $price, $qty);
	$stmt->execute();
	if($stmt->affected_rows) {
	 	echo "Insert id=" . $stmt->insert_id . "\n";
	}
	$stmt->close();
}

function update($conn, $id, $price, $qty) {
	$stmt = $conn->prepare('UPDATE test SET price = ?, qty = ? WHERE id = ?');
	$stmt->bind_param('dii', $price, $qty, $id);
	$stmt->execute();
	if($stmt->affected_rows) {
	 	echo "Updated id=" . $id . "\n";
	}
	$stmt->close();
}

function delete($conn, $id) {
	$stmt = $conn->prepare('DELETE FROM test WHERE id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	if($stmt->affected_rows) {
	 	echo "Deleted id=" . $id . "\n";
	}
	$stmt->close();
}

try {
    //select($conn);
    //select_by_cat($conn, 'test');
    //insert($conn, 'php', 0.10, 1);
    //insert($conn, 'php', 0.20, 2);
    //echo "total: " . select_total($conn, 'php') . "\n";
	//update($conn, 13, 0.99, 10);
	//delete($conn, 13);
    select_by_cat($conn, 'php');
} finally {
    $conn->close();
}
