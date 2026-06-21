<?php
if(!isLoggedIn()) {
    redirect("login");
}

$porudzbine = queryPrepared("SELECT * FROM porudzbine WHERE korisnik_id = :id ORDER BY datum DESC", [":id" => user()->id]);
?>

<div class="page-banner">
    <div class="container">
        <h1>Moje porudzbine</h1>
    </div>
</div>

<div class="container" style="padding:2.5rem 0 3rem;">
    <?php if(count($porudzbine) == 0) { ?>
        <div class="cart-empty-state">
            <h2>Nemate porudzbina</h2>
            <a href="<?= DOMAIN ?>index.php?page=books" class="btn-primary-custom">Pogledaj knjige</a>
        </div>
    <?php } else { ?>
        <?php foreach($porudzbine as $p) {
            $stavke = queryPrepared(
                "SELECT s.*, pr.naziv, pr.autor FROM stavke_porudzbine s JOIN proizvodi pr ON pr.id = s.proizvod_id WHERE s.porudzbina_id = :id",
                [":id" => $p->id]
            );
        ?>
            <div class="cart-item-row" style="display:block;">
                <div class="d-flex justify-content-between flex-wrap">
                    <strong>Porudzbina #<?= $p->id ?></strong>
                    <span><?= $p->datum ?></span>
                    <span class="cart-item-price">$<?= number_format($p->ukupno, 2) ?></span>
                </div>
                <hr>
                <?php foreach($stavke as $s) { ?>
                    <div class="summary-line">
                        <span><?= $s->naziv ?> (<?= $s->autor ?>) x <?= $s->kolicina ?></span>
                        <span>$<?= number_format($s->cena * $s->kolicina, 2) ?></span>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>
