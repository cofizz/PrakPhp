<?php

require_once __DIR__ . "/../config/conn.php";
require_once __DIR__ . "/../models/dbFunctions.php";
require_once __DIR__ . "/../models/funkcije.php";

if(!isAdmin()) {
    redirect("login");
}

$akcija = get("akcija");
$id = (int)get("id");

if($akcija == "aktiviraj") {
    execute("UPDATE korisnici SET aktiviran = 1, aktivacioni_kod = NULL WHERE id = :id", [":id" => $id]);
    setFlash("poruka", "Korisnik je aktiviran");
}

if($akcija == "otkljucaj") {
    execute("UPDATE korisnici SET zakljucan_do = NULL, neuspeli_pokusaji = 0, prvi_neuspeh = NULL WHERE id = :id", [":id" => $id]);
    setFlash("poruka", "Nalog je otkljucan");
}

if($akcija == "obrisi") {
    if($id == user()->id) {
        setFlash("greska", "Ne mozete obrisati sami sebe");
        redirect("admin&sekcija=korisnici");
    }

    execute("DELETE FROM stavke_porudzbine WHERE porudzbina_id IN (SELECT id FROM porudzbine WHERE korisnik_id = :id)", [":id" => $id]);
    execute("DELETE FROM porudzbine WHERE korisnik_id = :id", [":id" => $id]);
    execute("DELETE FROM korisnici WHERE id = :id", [":id" => $id]);

    setFlash("poruka", "Korisnik je obrisan");
}

redirect("admin&sekcija=korisnici");
