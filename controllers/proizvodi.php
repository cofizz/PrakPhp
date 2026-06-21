<?php

require_once __DIR__ . "/../config/conn.php";
require_once __DIR__ . "/../models/dbFunctions.php";
require_once __DIR__ . "/../models/funkcije.php";

if(!isAdmin()) {
    redirect("login");
}

$akcija = post("akcija");

if($akcija == "dodaj" || $akcija == "izmeni") {
    $naziv = trim(post("naziv"));
    $autor = trim(post("autor"));
    $kategorija_id = post("kategorija_id");
    $opis = trim(post("opis"));
    $cena = post("cena");
    $stara_cena = post("stara_cena");
    $godina = post("godina");
    $strane = post("strane");
    $ocena = post("ocena");
    $broj_recenzija = post("broj_recenzija");

    if(!$naziv || !$autor || !$kategorija_id || !$cena) {
        setFlash("greska", "Naziv, autor, kategorija i cena su obavezni");
        redirect("admin&sekcija=proizvodi");
    }

    if(!$stara_cena) {
        $stara_cena = null;
    }

    $slika = post("trenutna_slika");
    $thumbnail = post("trenutni_thumb");

    if(isset($_FILES["slika"]) && $_FILES["slika"]["error"] == 0) {
        $folderOrig = BASE_DIR . "uploads/orig";
        $folderThumb = BASE_DIR . "uploads/thumb";

        if(!is_dir($folderOrig)) {
            mkdir($folderOrig, 0777, true);
        }
        if(!is_dir($folderThumb)) {
            mkdir($folderThumb, 0777, true);
        }

        $ime = time() . "_" . rand(1000, 9999) . ".jpg";

        $putanjaOrig = $folderOrig . "/" . $ime;
        $putanjaThumb = $folderThumb . "/" . $ime;

        move_uploaded_file($_FILES["slika"]["tmp_name"], $putanjaOrig);

        napraviThumbnail($putanjaOrig, $putanjaThumb, 300);

        $slika = "uploads/orig/" . $ime;
        $thumbnail = "uploads/thumb/" . $ime;
    }

    if($akcija == "dodaj") {
        execute(
            "INSERT INTO proizvodi (kategorija_id, naziv, autor, opis, cena, stara_cena, godina, strane, ocena, broj_recenzija, slika, thumbnail)
             VALUES (:kategorija_id, :naziv, :autor, :opis, :cena, :stara_cena, :godina, :strane, :ocena, :broj_recenzija, :slika, :thumbnail)",
            [
                ":kategorija_id" => $kategorija_id,
                ":naziv" => $naziv,
                ":autor" => $autor,
                ":opis" => $opis,
                ":cena" => $cena,
                ":stara_cena" => $stara_cena,
                ":godina" => $godina,
                ":strane" => $strane,
                ":ocena" => $ocena,
                ":broj_recenzija" => $broj_recenzija,
                ":slika" => $slika,
                ":thumbnail" => $thumbnail
            ]
        );

        setFlash("poruka", "Knjiga je dodata");
    } else {
        $id = post("id");

        execute(
            "UPDATE proizvodi SET kategorija_id = :kategorija_id, naziv = :naziv, autor = :autor, opis = :opis,
             cena = :cena, stara_cena = :stara_cena, godina = :godina, strane = :strane, ocena = :ocena,
             broj_recenzija = :broj_recenzija, slika = :slika, thumbnail = :thumbnail WHERE id = :id",
            [
                ":kategorija_id" => $kategorija_id,
                ":naziv" => $naziv,
                ":autor" => $autor,
                ":opis" => $opis,
                ":cena" => $cena,
                ":stara_cena" => $stara_cena,
                ":godina" => $godina,
                ":strane" => $strane,
                ":ocena" => $ocena,
                ":broj_recenzija" => $broj_recenzija,
                ":slika" => $slika,
                ":thumbnail" => $thumbnail,
                ":id" => $id
            ]
        );

        setFlash("poruka", "Knjiga je izmenjena");
    }

    redirect("admin&sekcija=proizvodi");
}

if(get("akcija") == "obrisi") {
    $id = (int)get("id");

    execute("DELETE FROM stavke_porudzbine WHERE proizvod_id = :id", [":id" => $id]);
    execute("DELETE FROM proizvodi WHERE id = :id", [":id" => $id]);

    setFlash("poruka", "Knjiga je obrisana");
    redirect("admin&sekcija=proizvodi");
}

redirect("admin&sekcija=proizvodi");
