<?php

require_once __DIR__ . "/../config/conn.php";
require_once __DIR__ . "/../models/dbFunctions.php";
require_once __DIR__ . "/../models/funkcije.php";

if(!isAdmin()) {
    redirect("login");
}

$akcija = post("akcija");

if($akcija == "dodaj") {
    $naziv = trim(post("naziv"));
    $opis = trim(post("opis"));

    if(!$naziv) {
        setFlash("greska", "Naziv kategorije je obavezan");
        redirect("admin&sekcija=kategorije");
    }

    execute(
        "INSERT INTO kategorije (naziv, opis) VALUES (:naziv, :opis)",
        [":naziv" => $naziv, ":opis" => $opis]
    );

    setFlash("poruka", "Kategorija je dodata");
    redirect("admin&sekcija=kategorije");
}

if($akcija == "izmeni") {
    $id = post("id");
    $naziv = trim(post("naziv"));
    $opis = trim(post("opis"));

    execute(
        "UPDATE kategorije SET naziv = :naziv, opis = :opis WHERE id = :id",
        [":naziv" => $naziv, ":opis" => $opis, ":id" => $id]
    );

    setFlash("poruka", "Kategorija je izmenjena");
    redirect("admin&sekcija=kategorije");
}

if(get("akcija") == "obrisi") {
    $id = (int)get("id");

    $ima = queryOne("SELECT COUNT(*) AS broj FROM proizvodi WHERE kategorija_id = :id", [":id" => $id]);

    if($ima->broj > 0) {
        setFlash("greska", "Ne moze se obrisati kategorija koja ima knjige");
        redirect("admin&sekcija=kategorije");
    }

    execute("DELETE FROM kategorije WHERE id = :id", [":id" => $id]);

    setFlash("poruka", "Kategorija je obrisana");
    redirect("admin&sekcija=kategorije");
}

redirect("admin&sekcija=kategorije");
