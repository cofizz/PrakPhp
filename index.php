<?php

require_once "config/conn.php";
require_once "models/dbFunctions.php";
require_once "models/funkcije.php";

$page = get("page");

if(!$page) {
    $page = DEFAULT_PAGE;
}

$page = strtolower($page);

logujPristup($page);

require_once "views/fixed/header.php";

if(!file_exists("views/pages/" . $page . ".php")) {
    require_once "views/pages/404.php";
} else {
    require_once "views/pages/" . $page . ".php";
}

require_once "views/fixed/footer.php";
