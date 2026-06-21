<?php
$porudzbine = query("SELECT p.*, k.ime, k.prezime, k.email FROM porudzbine p JOIN korisnici k ON k.id = p.korisnik_id ORDER BY p.datum DESC");
?>

<h2>Porudzbine</h2>

<table class="admin-table">
    <tr><th>ID</th><th>Kupac</th><th>Adresa</th><th>Ukupno</th><th>Datum</th></tr>
    <?php foreach($porudzbine as $p) { ?>
        <tr>
            <td>#<?= $p->id ?></td>
            <td><?= $p->ime ?> <?= $p->prezime ?><br><small><?= $p->email ?></small></td>
            <td><?= $p->adresa ?></td>
            <td>$<?= number_format($p->ukupno, 2) ?></td>
            <td><?= $p->datum ?></td>
        </tr>
    <?php } ?>
</table>