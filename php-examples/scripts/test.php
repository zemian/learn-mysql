<?php

// Example to test simple CRUD operations
function create_conn() {
	$host = "localhost";
	$username = "zemian";
	$password = "test123";
	$dbname = "testdb";
	$conn = new mysqli($host, $username, $password, $dbname);
    return $conn;
}

function insert($conn, $cat, $price, $qty) {
	$ret = null;
	$stmt = $conn->prepare('INSERT INTO test(cat, price, qty) VALUES (?, ?, ?)');
	$stmt->bind_param('sdi', $cat, $price, $qty);
	if($stmt->execute()) {
		$ret = $stmt->insert_id;
	}
	$stmt->close();
	return $ret;
}

// Use this to test whether record exists
function select_by_id($conn, $id) {
	$ret = [];
	$stmt = $conn->prepare('SELECT id, cat, price, qty FROM test WHERE id = ?');
	$stmt->bind_param('i', $id);
	if ($stmt->execute()) {
		$result = $stmt->get_result();
		if($row = $result->fetch_assoc()) {
			array_push($ret, $row);
		}
		$result->close();
	}
	$stmt->close();
	return $ret;
}

// Throws exception if record does not exists.
function get_by_id($conn, $id) {
	$rows = select_by_id($conn, $id);
	if (count($rows) == 0) {
		throw new Exception("Id $id not found.");
	}
	$ret = $rows[0];
	return $ret;
}

function select_by_cat($conn, $cat) {
	$ret = [];
	$stmt = $conn->prepare('SELECT id, cat, price, qty FROM test WHERE cat = ?');
	$stmt->bind_param('s', $cat);
	if ($stmt->execute()) {
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			array_push($ret, $row);
		}
		$result->close();
	}
	$stmt->close();
	return $ret;
}

function get_total_by_cat($conn, $cat) {
	$ret = null;
	$stmt = $conn->prepare('SELECT sum(price) AS total FROM test WHERE cat = ?');
	$stmt->bind_param('s', $cat);
	if($stmt->execute()) {
		$result = $stmt->get_result();
		$ret = $result->fetch_row()[0];
		$result->close();
	}
	$stmt->close();
	return $ret;
}

function update($conn, $id, $price, $qty) {
	$ret = 0;
	$stmt = $conn->prepare('UPDATE test SET price = ?, qty = ? WHERE id = ?');
	$stmt->bind_param('dii', $price, $qty, $id);
	if ($stmt->execute()) {
		$ret = $stmt->affected_rows;
	}
	$stmt->close();
	return $ret;
}

function delete_by_id($conn, $id) {
	$ret = 0;
	$stmt = $conn->prepare('DELETE FROM test WHERE id = ?');
	$stmt->bind_param('i', $id);
	if ($stmt->execute()) {
		$ret = $stmt->affected_rows;
	}
	$stmt->close();
	return $ret;
}

function delete_by_cat($conn, $cat) {
	$ret = 0;
	$stmt = $conn->prepare('DELETE FROM test WHERE cat = ?');
	$stmt->bind_param('s', $cat);
	if ($stmt->execute()) {
		$ret = $stmt->affected_rows;
		$stmt->close();
	}
	return $ret;
}

// Test functions
function asserteq($actual, $expected, $reason = '') {
	if ($actual !== $expected) {
		throw new Exception("Failed on {$actual}, expected {$expected}. {$reason}");
	}
}
function isclose($a, $b) {
	if (abs($a - $b) < 0.00001) {
		return true;
	}
	return false;
}

$conn = create_conn();
try {
	$test_cat = substr(uniqid(), 0, 10);
	$test_count = 25;
	$test_ids = [];
	asserteq($test_cat !== '', true, "$test_cat");

	echo("Test insert with {$test_count} rows and cat={$test_cat}\n");
	for ($i = 1; $i <= $test_count; $i++) {
		$id = insert($conn, $test_cat, 0.10 + $i, $i);
		asserteq($id > 0, true, "{$id} > 0");
		array_push($test_ids, $id);
	}

	echo("Test select_by_cat\n");
	$rows = select_by_cat($conn, $test_cat);
	asserteq(count($rows), $test_count);
	asserteq($rows[0]['cat'], $test_cat);
	asserteq($rows[0]['price'] > 0.10, true, "{$rows[0]['price']}");
	asserteq($rows[0]['qty'], 1);
	asserteq($rows[1]['cat'], $test_cat);
	asserteq($rows[1]['price'] > 0.10, true, "{$rows[1]['price']}");
	asserteq($rows[1]['qty'], 2);

	echo("Test get_by_id\n");
	$row = get_by_id($conn, $test_ids[0]);
	asserteq($row['id'], $test_ids[0]);
	asserteq($row['cat'], $test_cat);
	asserteq(isclose($row['price'], 1.10), true, "{$row['price']}");
	asserteq($row['qty'], 1);

	echo("Test get_total_by_cat\n");
	$total = get_total_by_cat($conn, $test_cat);
	asserteq(isclose($total, 327.5000), true, "$total");

	echo("Test conn reconnect\n");
	$conn->close();
	$conn = create_conn();

	echo("Test update\n");
	$count = update($conn, $test_ids[0], 0.99, 998877);
	asserteq($count, 1);
	$row = get_by_id($conn, $test_ids[0]);
	asserteq($row['id'], $test_ids[0]);
	asserteq($row['cat'], $test_cat);
	asserteq(isclose($row['price'], 0.99), true, "{$row['price']}");
	asserteq($row['qty'], 998877);

	echo("Test delete_by_id\n");
	$count = delete_by_id($conn, $test_ids[0]);
	asserteq($count, 1);
	$rows = select_by_id($conn, $test_ids[0]);
	asserteq(count($rows), 0);

	echo("Test delete_by_cat\n");
	$count = delete_by_cat($conn, $test_cat);
	asserteq($count, $test_count - 1);
	$rows = select_by_cat($conn, $test_cat);
	asserteq(count($rows), 0);
} finally {
	$conn->close();
}
