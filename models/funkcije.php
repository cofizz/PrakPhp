<?php

function logujPristup($page) {
    $folder = BASE_DIR . "log";

    if(!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $fajl = $folder . "/pristup.txt";

    $ko = "gost";
    if(isLoggedIn()) {
        $ko = user()->email;
    }

    $ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "nepoznato";

    $linija = date("Y-m-d H:i:s") . " | " . $page . " | " . $ko . " | " . $ip . "\n";

    file_put_contents($fajl, $linija, FILE_APPEND);
}

function procitajLog() {
    $fajl = BASE_DIR . "log/pristup.txt";

    if(!file_exists($fajl)) {
        return [];
    }

    $linije = file($fajl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $rezultat = [];

    foreach($linije as $l) {
        $delovi = explode(" | ", $l);

        if(count($delovi) < 4) {
            continue;
        }

        $rezultat[] = [
            "vreme" => $delovi[0],
            "stranica" => $delovi[1],
            "korisnik" => $delovi[2],
            "ip" => $delovi[3]
        ];
    }

    return $rezultat;
}

function posaljiMail($za, $naslov, $poruka) {

    $folder = BASE_DIR . "mails";
    if(!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $ime = date("Y-m-d_His") . "_" . preg_replace("/[^a-z0-9]/i", "_", $za) . ".txt";
    $sadrzaj = "Za: " . $za . "\nNaslov: " . $naslov . "\nDatum: " . date("Y-m-d H:i:s") . "\n";
    $sadrzaj .= "-----------------------------------\n" . $poruka . "\n";
    file_put_contents($folder . "/" . $ime, $sadrzaj);

    require_once BASE_DIR . "phpmailer/src/Exception.php";
    require_once BASE_DIR . "phpmailer/src/PHPMailer.php";
    require_once BASE_DIR . "phpmailer/src/SMTP.php";

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS;

        $mail->setFrom(MAIL_USER, "ReadMore");
        $mail->addAddress($za);

        $mail->Subject = $naslov;
        $mail->Body = $poruka;

        $mail->send();
    }
    catch(Exception $e) {
        return false;
    }

    return true;
}

function slikaUrl($putanja) {
    if(!$putanja) {
        return "https://placehold.co/300x420/5c3d2e/f5e6c8?text=No+Cover";
    }

    if(substr($putanja, 0, 4) == "http") {
        return $putanja;
    }

    return DOMAIN . $putanja;
}

function napraviThumbnail($izvor, $cilj, $sirina) {
    $info = getimagesize($izvor);
    $tip = $info[2];

    if($tip == IMAGETYPE_JPEG) {
        $slika = imagecreatefromjpeg($izvor);
    } else if($tip == IMAGETYPE_PNG) {
        $slika = imagecreatefrompng($izvor);
    } else if($tip == IMAGETYPE_GIF) {
        $slika = imagecreatefromgif($izvor);
    } else {
        return false;
    }

    $staraSirina = imagesx($slika);
    $staraVisina = imagesy($slika);

    $visina = (int)($staraVisina * ($sirina / $staraSirina));

    $nova = imagecreatetruecolor($sirina, $visina);
    imagecopyresampled($nova, $slika, 0, 0, 0, 0, $sirina, $visina, $staraSirina, $staraVisina);

    imagejpeg($nova, $cilj, 85);

    imagedestroy($slika);
    imagedestroy($nova);

    return true;
}
