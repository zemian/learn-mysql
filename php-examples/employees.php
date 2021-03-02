<?php
$env = json_decode(file_get_contents("env.json"));
$dbcfg = $env->employeesdb;
$conn = new PDO($dbcfg->dns, $dbcfg->username, $dbcfg->passwd);
$stmt = $conn->query('SELECT * FROM employees ORDER BY RAND() LIMIT 25');
$employees = $stmt->fetchAll(PDO::FETCH_CLASS);
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
        <h1 class="title">Employees</h1>
        <table class="table is-fullwidth">
            <tr>
                <td>emp_no</td>
                <td>first_name</td>
                <td>last_name</td>
                <td>gender</td>
                <td>birth_date</td>
            </tr>
            <?php foreach ($employees as $emp) { ?>
            <tr>
                <td><?php echo $emp->emp_no; ?></td>
                <td><?php echo $emp->first_name; ?></td>
                <td><?php echo $emp->last_name; ?></td>
                <td><?php echo $emp->gender; ?></td>
                <td><?php echo $emp->birth_date; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
