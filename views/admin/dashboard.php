<?php
$brojKnjiga = queryOne("SELECT COUNT(*) AS broj FROM proizvodi", []);
$brojKategorija = queryOne("SELECT COUNT(*) AS broj FROM kategorije", []);
$brojPorudzbina = queryOne("SELECT COUNT(*) AS broj FROM porudzbine", []);
$brojKorisnika = queryOne("SELECT COUNT(*) AS broj FROM korisnici", []);

$danas = queryOne("SELECT COUNT(*) AS broj FROM korisnici WHERE DATE(poslednja_prijava) = CURDATE()", []);

$log = procitajLog();
$ukupnoPristupa = count($log);

$brojac = [];
foreach($log as $l) {
    $s = $l["stranica"];
    if(!isset($brojac[$s])) {
        $brojac[$s] = 0;
    }
    $brojac[$s]++;
}
arsort($brojac);
?>

<h2>Pocetna admin stranica</h2>

<div class="row g-3 mt-1 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-number"><?= $brojKnjiga->broj ?></div>
            <div class="stat-label">Knjiga</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-number"><?= $brojKategorija->broj ?></div>
            <div class="stat-label">Kategorija</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-number"><?= $brojPorudzbina->broj ?></div>
            <div class="stat-label">Porudzbina</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-number"><?= $danas->broj ?></div>
            <div class="stat-label">Prijava danas</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <h4>Statistika pristupa stranicama</h4>
        <p style="font-size:0.85rem; color:#8b6347;">Ukupno pristupa: <?= $ukupnoPristupa ?></p>

        <?php if($ukupnoPristupa == 0) { ?>
            <p>Jos nema zabelezenih pristupa.</p>
        <?php } else { ?>
            <?php foreach($brojac as $stranica => $broj) {
                $procenat = round(($broj / $ukupnoPristupa) * 100, 1);
            ?>
                <div class="bar-row">
                    <span style="width:90px;"><?= $stranica ?></span>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:<?= $procenat ?>%;"></div>
                    </div>
                    <span style="width:50px; text-align:right;"><?= $procenat ?>%</span>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="col-md-6">
        <h4>Poslednji pristupi</h4>
        <table class="admin-table">
            <tr><th>Vreme</th><th>Stranica</th><th>Korisnik</th></tr>
            <?php
            $poslednji = array_slice(array_reverse($log), 0, 12);
            foreach($poslednji as $l) {
            ?>
                <tr>
                    <td><?= $l["vreme"] ?></td>
                    <td><?= $l["stranica"] ?></td>
                    <td><?= $l["korisnik"] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
