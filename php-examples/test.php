<?php

// Example to test simple CRUD operations
$host = "localhost";
$username = "zemian";
$password = "test123";
$dbname = "testdb";
$conn = new mysqli($host, $username, $password, $dbname);

function select_all($conn) {
	$ret = [];
	$result = $conn->query('SELECT * FROM test');
	while($row = $result->fetch_assoc()) {
		array_push($ret, $row);
	}
	$result->close();
	return $ret;
}

function select_by_id($conn, $id) {
	$stmt = $conn->prepare('SELECT * FROM test WHERE id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$ret = $result->fetch_assoc();
	$result->close();
	$stmt->close();
	return $ret;
}

function select_by_cat($conn, $cat) {
	$ret = [];
	$stmt = $conn->prepare('SELECT * FROM test WHERE cat = ?');
	$stmt->bind_param('s', $cat);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
		array_push($ret, $row);
	}
	$result->close();
	$stmt->close();
	return $ret;
}

function select_total($conn, $cat) {
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
	$ret = null;
	$stmt = $conn->prepare('INSERT INTO test(cat, price, qty) VALUES (?, ?, ?)');
	$stmt->bind_param('sdi', $cat, $price, $qty);
	$stmt->execute();
	if($stmt->affected_rows) {
		$ret = $stmt->insert_id;
	}
	$stmt->close();
	return $ret;
}

function update($conn, $id, $price, $qty) {
	$ret = null;
	$stmt = $conn->prepare('UPDATE test SET price = ?, qty = ? WHERE id = ?');
	$stmt->bind_param('dii', $price, $qty, $id);
	$stmt->execute();
	if($stmt->affected_rows) {
		$ret = $id;
	}
	$stmt->close();
	return $ret;
}

function delete($conn, $id) {
	$ret = null;
	$stmt = $conn->prepare('DELETE FROM test WHERE id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	if($stmt->affected_rows) {
		$ret = $id;
	}
	$stmt->close();
	return $ret;
}

function delete_by_cat($conn, $cat) {
	$stmt = $conn->prepare('DELETE FROM test WHERE cat = ?');
	$stmt->bind_param('s', $cat);
	$stmt->execute();
	$ret = $stmt->affected_rows;
	$stmt->close();
	return $ret;
}


try {
	var_dump(select_all($conn));
	// var_dump(select_by_id($conn, 1));
	// var_dump(select_by_cat($conn, 'test'));

	// var_dump(insert($conn, 'php', 0.10, 1));
	// var_dump(insert($conn, 'php', 0.20, 2));
	// var_dump(select_by_cat($conn, 'php'));
	
	// var_dump(select_total($conn, 'php'));
	// var_dump(update($conn, 26, 0.99, 10));
	// var_dump(select_total($conn, 'php'));
	
	// var_dump(select_by_id($conn, 26));
	// var_dump(delete($conn, 26));
	// var_dump(select_by_id($conn, 26));

	// var_dump(select_by_cat($conn, 'php'));
	// var_dump(delete_by_cat($conn, 'php'));
	// var_dump(select_by_cat($conn, 'php'));
} finally {
	$conn->close();
}
