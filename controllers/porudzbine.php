<?php

require_once __DIR__ . "/../config/conn.php";
require_once __DIR__ . "/../models/dbFunctions.php";
require_once __DIR__ . "/../models/funkcije.php";

$akcija = post("akcija");

if($akcija == "kupi") {

    if(!isLoggedIn()) {
        setFlash("greska", "Morate biti prijavljeni da biste kupili");
        redirect("login");
    }

    if(!isset($_SESSION["korpa"]) || count($_SESSION["korpa"]) == 0) {
        setFlash("greska", "Korpa je prazna");
        redirect("cart");
    }

    $adresa = trim(post("adresa"));

    if(!preg_match('/^[A-Za-zČĆŽŠĐčćžšđ ]{3,}\s+\d+.*$/u', $adresa)) {
        setFlash("greska", "Adresa nije u ispravnom formatu, primer: Knez Mihailova 12");
        redirect("cart");
    }

    $ukupno = 0;

    foreach($_SESSION["korpa"] as $id => $kolicina) {
        $p = getById("proizvodi", $id);
        if($p) {
            $ukupno += $p->cena * $kolicina;
        }
    }

    $porudzbinaId = execute(
        "INSERT INTO porudzbine (korisnik_id, ukupno, adresa) VALUES (:korisnik_id, :ukupno, :adresa)",
        [
            ":korisnik_id" => user()->id,
            ":ukupno" => $ukupno,
            ":adresa" => $adresa
        ]
    );

    foreach($_SESSION["korpa"] as $id => $kolicina) {
        $p = getById("proizvodi", $id);
        if($p) {
            execute(
                "INSERT INTO stavke_porudzbine (porudzbina_id, proizvod_id, kolicina, cena)
                 VALUES (:porudzbina_id, :proizvod_id, :kolicina, :cena)",
                [
                    ":porudzbina_id" => $porudzbinaId,
                    ":proizvod_id" => $id,
                    ":kolicina" => $kolicina,
                    ":cena" => $p->cena
                ]
            );
        }
    }

    $_SESSION["korpa"] = [];

    setFlash("poruka", "Porudzbina je uspesno kreirana, hvala na kupovini");
    redirect("orders");
}

redirect("home");
