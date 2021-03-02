<?php
$config = json_decode(file_get_contents("../env.json"));
$db = $config->employeesdb;
$conn = new PDO($db->dns, $db->username, $db->passwd);

header('Content-Type: application/json');

$stmt = $conn->query('SELECT * FROM employees ORDER BY RAND() LIMIT 25');
$employees = $stmt->fetchAll(PDO::FETCH_CLASS);
echo json_encode(array(
	"items" => $employees
));
