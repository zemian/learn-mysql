<?php
function app_header() {

echo <<<HERE
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employees App</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/bulma@0.9.1/css/bulma.css">
</head>
<body>
<section class="section">
HERE;

}

function app_footer() {

echo <<<HERE
</section>
</body>
</html>
HERE;

	// Close db $conn if there is any
	if (isset($conn)) {
		$conn->close();
	}

}

function app_create_conn() {
	$db_config = [
	    "servername" => "localhost",
	    "username" => "zemian",
	    "password" => "test123",
	    "dbname" => "employees"
	];
    $conn = new mysqli($db_config["servername"], $db_config["username"], $db_config["password"], $db_config["dbname"]);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}