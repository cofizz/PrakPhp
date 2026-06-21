<?php

require_once __DIR__ . "/../config/conn.php";
require_once __DIR__ . "/../models/dbFunctions.php";
require_once __DIR__ . "/../models/funkcije.php";

if(!isset($_SESSION["korpa"])) {
    $_SESSION["korpa"] = [];
}

$akcija = get("akcija");

if($akcija == "dodaj") {
    $id = (int)get("id");

    $proizvod = getById("proizvodi", $id);

    if($proizvod) {
        if(isset($_SESSION["korpa"][$id])) {
            $_SESSION["korpa"][$id]++;
        } else {
            $_SESSION["korpa"][$id] = 1;
        }
        setFlash("poruka", "Knjiga je dodata u korpu");
    }

    redirect("books");
}

if($akcija == "izbaci") {
    $id = (int)get("id");

    if(isset($_SESSION["korpa"][$id])) {
        unset($_SESSION["korpa"][$id]);
    }

    redirect("cart");
}

if($akcija == "uvecaj") {
    $id = (int)get("id");

    if(isset($_SESSION["korpa"][$id])) {
        $_SESSION["korpa"][$id]++;
    }

    redirect("cart");
}

if($akcija == "umanji") {
    $id = (int)get("id");

    if(isset($_SESSION["korpa"][$id])) {
        $_SESSION["korpa"][$id]--;
        if($_SESSION["korpa"][$id] <= 0) {
            unset($_SESSION["korpa"][$id]);
        }
    }

    redirect("cart");
}

redirect("cart");
