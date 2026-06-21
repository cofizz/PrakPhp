<?php
$kategorije = getAll("kategorije");

$uredi = get("uredi");
$knjiga = null;
if($uredi) {
    $knjiga = getById("proizvodi", (int)$uredi);
}

$proizvodi = query("SELECT p.*, k.naziv AS kategorija FROM proizvodi p JOIN kategorije k ON k.id = p.kategorija_id ORDER BY p.id DESC");
?>

<h2>Upravljanje knjigama</h2>

<div class="p-4 mb-4" style="background:#f5e6c8; border:1px solid #d4b896; border-radius:8px;">
    <h4><?= $knjiga ? "Izmena knjige" : "Dodaj novu knjigu" ?></h4>

    <form method="post" action="<?= DOMAIN ?>controllers/proizvodi.php" enctype="multipart/form-data">
        <input type="hidden" name="akcija" value="<?= $knjiga ? "izmeni" : "dodaj" ?>">
        <?php if($knjiga) { ?>
            <input type="hidden" name="id" value="<?= $knjiga->id ?>">
            <input type="hidden" name="trenutna_slika" value="<?= $knjiga->slika ?>">
            <input type="hidden" name="trenutni_thumb" value="<?= $knjiga->thumbnail ?>">
        <?php } ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label>Naziv</label>
                <input type="text" name="naziv" class="form-control" value="<?= $knjiga ? $knjiga->naziv : "" ?>" required>
            </div>
            <div class="col-md-6">
                <label>Autor</label>
                <input type="text" name="autor" class="form-control" value="<?= $knjiga ? $knjiga->autor : "" ?>" required>
            </div>
            <div class="col-md-4">
                <label>Kategorija</label>
                <select name="kategorija_id" class="form-select">
                    <?php foreach($kategorije as $k) { ?>
                        <option value="<?= $k->id ?>" <?= $knjiga && $knjiga->kategorija_id == $k->id ? "selected" : "" ?>><?= $k->naziv ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Cena</label>
                <input type="number" step="0.01" name="cena" class="form-control" value="<?= $knjiga ? $knjiga->cena : "" ?>" required>
            </div>
            <div class="col-md-4">
                <label>Stara cena</label>
                <input type="number" step="0.01" name="stara_cena" class="form-control" value="<?= $knjiga ? $knjiga->stara_cena : "" ?>">
            </div>
            <div class="col-md-3">
                <label>Godina</label>
                <input type="number" name="godina" class="form-control" value="<?= $knjiga ? $knjiga->godina : "" ?>">
            </div>
            <div class="col-md-3">
                <label>Strane</label>
                <input type="number" name="strane" class="form-control" value="<?= $knjiga ? $knjiga->strane : "" ?>">
            </div>
            <div class="col-md-3">
                <label>Ocena</label>
                <input type="number" step="0.1" name="ocena" class="form-control" value="<?= $knjiga ? $knjiga->ocena : "" ?>">
            </div>
            <div class="col-md-3">
                <label>Broj recenzija</label>
                <input type="number" name="broj_recenzija" class="form-control" value="<?= $knjiga ? $knjiga->broj_recenzija : "" ?>">
            </div>
            <div class="col-12">
                <label>Opis</label>
                <textarea name="opis" class="form-control" rows="2"><?= $knjiga ? $knjiga->opis : "" ?></textarea>
            </div>
            <div class="col-12">
                <label>Slika (JPG/PNG) - pravi se thumbnail i originalna verzija</label>
                <input type="file" name="slika" class="form-control" accept="image/*">
            </div>
        </div>

        <button type="submit" class="btn-submit-form mt-3"><?= $knjiga ? "Sacuvaj izmene" : "Dodaj knjigu" ?></button>
        <?php if($knjiga) { ?>
            <a href="<?= DOMAIN ?>index.php?page=admin&sekcija=proizvodi" class="btn-reset">Odustani</a>
        <?php } ?>
    </form>
</div>

<table class="admin-table">
    <tr><th>ID</th><th>Slika</th><th>Naziv</th><th>Autor</th><th>Kategorija</th><th>Cena</th><th></th></tr>
    <?php foreach($proizvodi as $p) { ?>
        <tr>
            <td><?= $p->id ?></td>
            <td><img src="<?= slikaUrl($p->thumbnail) ?>" alt=""></td>
            <td><?= $p->naziv ?></td>
            <td><?= $p->autor ?></td>
            <td><?= $p->kategorija ?></td>
            <td>$<?= number_format($p->cena, 2) ?></td>
            <td>
                <a class="btn-mini" href="<?= DOMAIN ?>index.php?page=admin&sekcija=proizvodi&uredi=<?= $p->id ?>">Izmeni</a>
                <a class="btn-mini danger" href="<?= DOMAIN ?>controllers/proizvodi.php?akcija=obrisi&id=<?= $p->id ?>" onclick="return confirm('Obrisati knjigu?');">Obrisi</a>
            </td>
        </tr>
    <?php } ?>
</table>
