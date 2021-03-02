<?php
$env = json_decode(file_get_contents("env.json"));
$dbcfg = $env->testdb;
$conn = new PDO($dbcfg->dns, $dbcfg->username, $dbcfg->passwd);
$stmt = $conn->query('SELECT 1 + 1');
$rows = $stmt->fetch();
$db_test_result = $rows[0];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Learn MySQL</title>
    <link rel="stylesheet" href="https://unpkg.com/bulma">
</head>
<body>

<div class="hero is-primary">
    <div class="hero-body">
        <h1 class="title"><a href="/">Learn MySQL Database</a></h1>
    </div>
</div>
<div class="section">
    <div class="container">
        <h1 class="title">Test Result</h1>
        <pre><?php print_r($db_test_result); ?></pre>
    </div>
</div>

</body>
</html>
