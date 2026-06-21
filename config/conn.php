<?php

require_once __DIR__ . "/common.php";

try {

    $con = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME . ";charset=utf8", USERNAME, PASSWORD);

    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex) {
    echo $ex->getMessage();
}
