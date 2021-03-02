<?php
header('Content-Type: application/json');

$offset = $_GET['offset'] ?? 0;
$limit = min($_GET['limit'] ?? 25, 500);
$total_count = $_GET['total_count'] ?? false;
//print_r([$offset, $limit, $total_count]);

$env = json_decode(file_get_contents("../env.json"));
$dbcfg = $env->employeesdb;
$conn = new PDO($dbcfg->dns, $dbcfg->username, $dbcfg->passwd);

$stmt = $conn->prepare('SELECT * FROM employees LIMIT ?, ?');
$stmt->bindValue(1, $offset, PDO::PARAM_INT);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->execute();

$employees = $stmt->fetchAll(PDO::FETCH_CLASS);
$result = array(
	"items" => $employees
);
if ($total_count) {
	$stmt = $conn->query("SELECT count(*) FROM employees");
	$result['total_count'] = $stmt->fetch()[0];
}

echo json_encode($result);
