<?php
header('Content-Type: application/json');

$config = json_decode(file_get_contents("../env.json"));
$db = $config->employeesdb;
$conn = new PDO($db->dns, $db->username, $db->passwd);
$stmt = $conn->query('SELECT * FROM employees ORDER BY RAND() LIMIT 25');
$employees = $stmt->fetchAll(PDO::FETCH_CLASS);
echo json_encode(array(
	"items" => $employees
));
