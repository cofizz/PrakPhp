<?php
$uredi = get("uredi");
$kat = null;
if($uredi) {
    $kat = getById("kategorije", (int)$uredi);
}

$kategorije = getAll("kategorije");
?>

<h2>Upravljanje kategorijama</h2>

<div class="p-4 mb-4" style="background:#f5e6c8; border:1px solid #d4b896; border-radius:8px;">
    <h4><?= $kat ? "Izmena kategorije" : "Dodaj kategoriju" ?></h4>

    <form method="post" action="<?= DOMAIN ?>controllers/kategorije.php">
        <input type="hidden" name="akcija" value="<?= $kat ? "izmeni" : "dodaj" ?>">
        <?php if($kat) { ?>
            <input type="hidden" name="id" value="<?= $kat->id ?>">
        <?php } ?>

        <div class="row g-3">
            <div class="col-md-4">
                <label>Naziv</label>
                <input type="text" name="naziv" class="form-control" value="<?= $kat ? $kat->naziv : "" ?>" required>
            </div>
            <div class="col-md-8">
                <label>Opis</label>
                <input type="text" name="opis" class="form-control" value="<?= $kat ? $kat->opis : "" ?>">
            </div>
        </div>

        <button type="submit" class="btn-submit-form mt-3"><?= $kat ? "Sacuvaj" : "Dodaj" ?></button>
        <?php if($kat) { ?>
            <a href="<?= DOMAIN ?>index.php?page=admin&sekcija=kategorije" class="btn-reset">Odustani</a>
        <?php } ?>
    </form>
</div>

<table class="admin-table">
    <tr><th>ID</th><th>Naziv</th><th>Opis</th><th></th></tr>
    <?php foreach($kategorije as $k) { ?>
        <tr>
            <td><?= $k->id ?></td>
            <td><?= $k->naziv ?></td>
            <td><?= $k->opis ?></td>
            <td>
                <a class="btn-mini" href="<?= DOMAIN ?>index.php?page=admin&sekcija=kategorije&uredi=<?= $k->id ?>">Izmeni</a>
                <a class="btn-mini danger" href="<?= DOMAIN ?>controllers/kategorije.php?akcija=obrisi&id=<?= $k->id ?>" onclick="return confirm('Obrisati kategoriju?');">Obrisi</a>
            </td>
        </tr>
    <?php } ?>
</table>
