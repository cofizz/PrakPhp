<?php

function query($query) {
    global $con;

    return $con->query($query)->fetchAll();
}

function queryPrepared($query, $params) {
    global $con;

    $stmt = $con->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function queryOne($query, $params) {
    global $con;

    $stmt = $con->prepare($query);
    $stmt->execute($params);

    return $stmt->fetch();
}

function execute($query, $params) {
    global $con;

    $stmt = $con->prepare($query);
    $stmt->execute($params);

    return $con->lastInsertId();
}

function getAll($tableName) {
    return query("SELECT * FROM $tableName");
}

function getById($tableName, $id) {
    return queryOne("SELECT * FROM $tableName WHERE id = :id", [":id" => $id]);
}
