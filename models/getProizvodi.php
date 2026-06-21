<?php

require_once __DIR__ . "/../config/conn.php";
require_once __DIR__ . "/dbFunctions.php";

$search = get("search");
$kategorija = get("kategorija");
$sort = get("sort");
$strana = get("strana");
$poStrani = 8;

if(!$strana || (int)$strana < 1) {
    $strana = 1;
}
$strana = (int)$strana;

$offset = ($strana - 1) * $poStrani;

$where = " WHERE 1 ";
$params = [];

if($search) {
    $where .= " AND (p.naziv LIKE :s OR p.autor LIKE :s2) ";
    $params[":s"] = "%" . $search . "%";
    $params[":s2"] = "%" . $search . "%";
}

if($kategorija) {
    $where .= " AND p.kategorija_id = :k ";
    $params[":k"] = $kategorija;
}

$order = " ORDER BY p.id ASC ";

if($sort == "price-low") {
    $order = " ORDER BY p.cena ASC ";
} else if($sort == "price-high") {
    $order = " ORDER BY p.cena DESC ";
} else if($sort == "rating") {
    $order = " ORDER BY p.ocena DESC ";
} else if($sort == "title") {
    $order = " ORDER BY p.naziv ASC ";
} else if($sort == "year-new") {
    $order = " ORDER BY p.godina DESC ";
} else if($sort == "year-old") {
    $order = " ORDER BY p.godina ASC ";
}

$ukupnoRez = queryOne("SELECT COUNT(*) AS broj FROM proizvodi p " . $where, $params);
$ukupno = $ukupnoRez->broj;

$proizvodi = queryPrepared(
    "SELECT p.*, k.naziv AS kategorija FROM proizvodi p
     JOIN kategorije k ON k.id = p.kategorija_id
     " . $where . $order . " LIMIT $poStrani OFFSET $offset",
    $params
);

$brojStrana = ceil($ukupno / $poStrani);

json([
    "proizvodi" => $proizvodi,
    "ukupno" => (int)$ukupno,
    "strana" => $strana,
    "brojStrana" => (int)$brojStrana
]);
