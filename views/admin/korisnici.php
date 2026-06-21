<?php
$korisnici = query("SELECT * FROM korisnici ORDER BY id ASC");
?>

<h2>Korisnici</h2>

<table class="admin-table">
    <tr><th>ID</th><th>Ime</th><th>Email</th><th>Uloga</th><th>Aktiviran</th><th>Zakljucan</th><th></th></tr>
    <?php foreach($korisnici as $k) {
        $zakljucan = $k->zakljucan_do != null && strtotime($k->zakljucan_do) > time();
    ?>
        <tr>
            <td><?= $k->id ?></td>
            <td><?= $k->ime ?> <?= $k->prezime ?></td>
            <td><?= $k->email ?></td>
            <td><?= $k->uloga ?></td>
            <td><?= $k->aktiviran ? "da" : "ne" ?></td>
            <td><?= $zakljucan ? "da" : "ne" ?></td>
            <td>
                <?php if(!$k->aktiviran) { ?>
                    <a class="btn-mini ok" href="<?= DOMAIN ?>controllers/korisnici.php?akcija=aktiviraj&id=<?= $k->id ?>">Aktiviraj</a>
                <?php } ?>
                <?php if($zakljucan) { ?>
                    <a class="btn-mini" href="<?= DOMAIN ?>controllers/korisnici.php?akcija=otkljucaj&id=<?= $k->id ?>">Otkljucaj</a>
                <?php } ?>
                <a class="btn-mini danger" href="<?= DOMAIN ?>controllers/korisnici.php?akcija=obrisi&id=<?= $k->id ?>" onclick="return confirm('Obrisati korisnika?');">Obrisi</a>
            </td>
        </tr>
    <?php } ?>
</table>
