<?php

require_once __DIR__ . "/../config/conn.php";
require_once __DIR__ . "/../models/dbFunctions.php";
require_once __DIR__ . "/../models/funkcije.php";

$akcija = post("akcija");

if($akcija == "register") {
    $ime = trim(post("ime"));
    $prezime = trim(post("prezime"));
    $email = trim(post("email"));
    $lozinka = post("lozinka");
    $lozinka2 = post("lozinka2");

    if(!$ime || !$prezime || !$email || !$lozinka) {
        setFlash("greska", "Sva polja su obavezna");
        redirect("register");
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash("greska", "Email nije ispravan");
        redirect("register");
    }

    if($lozinka != $lozinka2) {
        setFlash("greska", "Lozinke se ne poklapaju");
        redirect("register");
    }

    if(strlen($lozinka) < 6) {
        setFlash("greska", "Lozinka mora imati bar 6 karaktera");
        redirect("register");
    }

    $postoji = queryOne("SELECT * FROM korisnici WHERE email = :e", [":e" => $email]);

    if($postoji) {
        setFlash("greska", "Korisnik sa tim emailom vec postoji");
        redirect("register");
    }

    $hash = password_hash($lozinka, PASSWORD_DEFAULT);
    $kod = md5(uniqid());

    execute(
        "INSERT INTO korisnici (ime, prezime, email, lozinka, uloga, aktiviran, aktivacioni_kod)
         VALUES (:ime, :prezime, :email, :lozinka, 'user', 0, :kod)",
        [
            ":ime" => $ime,
            ":prezime" => $prezime,
            ":email" => $email,
            ":lozinka" => $hash,
            ":kod" => $kod
        ]
    );

    $link = DOMAIN . "index.php?page=activate&kod=" . $kod;

    $poruka = "evo linka: " . $link;

    posaljiMail($email, "Aktivacija naloga - ReadMore", $poruka);

    setFlash("poruka", "Registracija uspesna, aktivacioni link je u mails folderu");
    redirect("login");
}

if($akcija == "login") {
    $email = trim(post("email"));
    $lozinka = post("lozinka");

    $korisnik = queryOne("SELECT * FROM korisnici WHERE email = :e", [":e" => $email]);

    if(!$korisnik) {
        setFlash("greska", "Pogresan email ili lozinka");
        redirect("login");
    }

    if($korisnik->zakljucan_do != null && strtotime($korisnik->zakljucan_do) > time()) {
        setFlash("greska", "Nalog je zakljucan zbog vise neuspelih pokusaja, pokusajte posle");
        redirect("login");
    }

    if(!password_verify($lozinka, $korisnik->lozinka)) {

        $sada = time();
        $prvi = $korisnik->prvi_neuspeh != null ? strtotime($korisnik->prvi_neuspeh) : 0;

        if($prvi == 0 || ($sada - $prvi) > 300) {
            $brojac = 1;
            execute(
                "UPDATE korisnici SET neuspeli_pokusaji = 1, prvi_neuspeh = NOW() WHERE id = :id",
                [":id" => $korisnik->id]
            );
        } else {
            $brojac = $korisnik->neuspeli_pokusaji + 1;
            execute(
                "UPDATE korisnici SET neuspeli_pokusaji = :b WHERE id = :id",
                [":b" => $brojac, ":id" => $korisnik->id]
            );
        }

        if($brojac >= 3) {
            execute(
                "UPDATE korisnici SET zakljucan_do = DATE_ADD(NOW(), INTERVAL 15 MINUTE), neuspeli_pokusaji = 0, prvi_neuspeh = NULL WHERE id = :id",
                [":id" => $korisnik->id]
            );

            $poruka = "Zdravo " . $korisnik->ime . ",\n\n";
            $poruka .= "Detektovali smo 3 neuspela pokusaja prijave na vas nalog u poslednjih 5 minuta.\n";
            $poruka .= "Iz bezbednosnih razloga nalog je privremeno zakljucan na 15 minuta.\n";
            $poruka .= "Ako to niste bili vi, preporucujemo da promenite lozinku.\n";

            posaljiMail($korisnik->email, "Upozorenje - neuspeli pokusaji prijave", $poruka);

            setFlash("greska", "Nalog je zakljucan zbog 3 neuspela pokusaja, poslat vam je mail upozorenja");
            redirect("login");
        }

        setFlash("greska", "Pogresan email ili lozinka");
        redirect("login");
    }

    if($korisnik->aktiviran == 0) {
        setFlash("greska", "Nalog nije aktiviran, proverite mail za link");
        redirect("login");
    }

    execute(
        "UPDATE korisnici SET neuspeli_pokusaji = 0, prvi_neuspeh = NULL, zakljucan_do = NULL, poslednja_prijava = NOW() WHERE id = :id",
        [":id" => $korisnik->id]
    );

    $korisnik->lozinka = null;
    $_SESSION["user"] = $korisnik;

    if($korisnik->uloga == "admin") {
        redirect("admin");
    }

    redirect("home");
}

if($akcija == "activate") {
    $kod = post("kod");

    $korisnik = queryOne("SELECT * FROM korisnici WHERE aktivacioni_kod = :kod", [":kod" => $kod]);

    if(!$korisnik) {
        setFlash("greska", "Aktivacioni kod nije ispravan ili je nalog vec aktiviran");
        redirect("login");
    }

    execute("UPDATE korisnici SET aktiviran = 1, aktivacioni_kod = NULL WHERE id = :id", [":id" => $korisnik->id]);

    setFlash("poruka", "Nalog uspesno aktiviran");
    redirect("login");
}

if($akcija == "logout") {
    unset($_SESSION["user"]);
    redirect("home");
}

redirect("home");
