<?php
if(!isAdmin()) {
    redirect("login");
}

$sekcija = get("sekcija");

if(!$sekcija) {
    $sekcija = "dashboard";
}

$dozvoljene = ["dashboard", "proizvodi", "kategorije", "porudzbine", "korisnici"];

if(!in_array($sekcija, $dozvoljene)) {
    $sekcija = "dashboard";
}
?>

<div class="container">
    <div class="admin-wrap">
        <div class="admin-sidebar">
            <h5 style="color:#f5e6c8; padding:0.5rem;">Admin panel</h5>
            <a href="<?= DOMAIN ?>index.php?page=admin&sekcija=dashboard" class="<?= $sekcija == "dashboard" ? "active" : "" ?>">Pocetna</a>
            <a href="<?= DOMAIN ?>index.php?page=admin&sekcija=proizvodi" class="<?= $sekcija == "proizvodi" ? "active" : "" ?>">Knjige</a>
            <a href="<?= DOMAIN ?>index.php?page=admin&sekcija=kategorije" class="<?= $sekcija == "kategorije" ? "active" : "" ?>">Kategorije</a>
            <a href="<?= DOMAIN ?>index.php?page=admin&sekcija=porudzbine" class="<?= $sekcija == "porudzbine" ? "active" : "" ?>">Porudzbine</a>
            <a href="<?= DOMAIN ?>index.php?page=admin&sekcija=korisnici" class="<?= $sekcija == "korisnici" ? "active" : "" ?>">Korisnici</a>
        </div>

        <div class="admin-content">
            <?php require_once BASE_DIR . "views/admin/" . $sekcija . ".php"; ?>
        </div>
    </div>
</div>
