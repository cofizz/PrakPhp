<?php

session_start();

define("BASE_DIR", dirname(__DIR__) . "/");

$configFile = BASE_DIR . "config/.env";

$configRows = file($configFile);

foreach($configRows as $c) {
    $c = trim($c);
    if($c === "") {
        continue;
    }

    $key = explode("=", $c)[0];
    $value = explode("=", $c)[1];

    define(trim($key), trim($value));
}

$appUrl = str_replace("\\", "/", dirname(__DIR__));
$docRoot = str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]);
$base = str_replace($docRoot, "", $appUrl);
$protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ? "https" : "http";

define("DOMAIN", $protocol . "://" . $_SERVER["HTTP_HOST"] . $base . "/");

function setFlash($key, $value) {
    $_SESSION[$key] = $value;
}

function getFlash($key) {
    if(!hasFlash($key)) {
        return null;
    }

    $flashData = $_SESSION[$key];

    unset($_SESSION[$key]);

    return $flashData;
}

function hasFlash($key) {
    return isset($_SESSION[$key]) && $_SESSION[$key];
}

function get($key) {
    if(isset($_GET[$key])) {
        return $_GET[$key];
    }

    return null;
}

function post($key) {
    if(isset($_POST[$key])) {
        return $_POST[$key];
    }

    return null;
}

function json($data) {
    header("Content-type: application/json");
    echo json_encode($data);
}

function redirect($page) {
    header("Location: " . DOMAIN . "index.php?page=" . $page);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION["user"]);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION["user"]->uloga == "admin";
}

function user() {
    if(isLoggedIn()) {
        return $_SESSION["user"];
    }

    return null;
}
