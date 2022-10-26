<?php

spl_autoload_register(function($classname) {
    include "classes/$classname.php";
});

// connect to db
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new Database(Config::$db["host"], Config::$db["user"], Config::$db["pass"], Config::$db["database"]);

$q = $db->query("select * from User;");
print_r($q);
// print($_SERVER['HTTP_X_FORWARDED_FOR']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>
    <body>
        <h1>Testing</h1>
    </body>
</html>