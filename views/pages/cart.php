<?php
if(!isset($_SESSION["korpa"])) {
    $_SESSION["korpa"] = [];
}

$stavke = [];
$ukupno = 0;

foreach($_SESSION["korpa"] as $id => $kolicina) {
    $p = getById("proizvodi", $id);
    if($p) {
        $p->kolicina = $kolicina;
        $p->medjuzbir = $p->cena * $kolicina;
        $ukupno += $p->medjuzbir;
        $stavke[] = $p;
    }
}
?>

<div class="page-banner">
    <div class="container">
        <h1>Vasa korpa</h1>
    </div>
</div>

<div class="container" style="padding:2.5rem 0 3rem;">
    <?php if(count($stavke) == 0) { ?>
        <div class="cart-empty-state">
            <h2>Korpa je prazna</h2>
            <p>Dodajte neku knjigu u korpu.</p>
            <a href="<?= DOMAIN ?>index.php?page=books" class="btn-primary-custom">Pogledaj knjige</a>
        </div>
    <?php } else { ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <?php foreach($stavke as $s) { ?>
                    <div class="cart-item-row">
                        <img src="<?= slikaUrl($s->thumbnail) ?>" class="cart-item-cover" alt="<?= $s->naziv ?>">
                        <div class="cart-item-details">
                            <div class="cart-item-title"><?= $s->naziv ?></div>
                            <div class="cart-item-author"><?= $s->autor ?></div>
                            <div class="cart-item-price">$<?= number_format($s->cena, 2) ?></div>
                        </div>
                        <div class="qty-controls">
                            <a class="qty-btn" href="<?= DOMAIN ?>controllers/korpa.php?akcija=umanji&id=<?= $s->id ?>">-</a>
                            <span class="qty-number"><?= $s->kolicina ?></span>
                            <a class="qty-btn" href="<?= DOMAIN ?>controllers/korpa.php?akcija=uvecaj&id=<?= $s->id ?>">+</a>
                        </div>
                        <a class="btn-remove" href="<?= DOMAIN ?>controllers/korpa.php?akcija=izbaci&id=<?= $s->id ?>">Ukloni</a>
                    </div>
                <?php } ?>
            </div>

            <div class="col-lg-4">
                <div class="order-summary-box">
                    <h3>Pregled porudzbine</h3>
                    <div class="summary-line">
                        <span>Stavki</span>
                        <span><?= array_sum($_SESSION["korpa"]) ?></span>
                    </div>
                    <div class="summary-line total">
                        <span>Ukupno</span>
                        <span class="total-amount">$<?= number_format($ukupno, 2) ?></span>
                    </div>

                    <?php if(isLoggedIn()) { ?>
                        <form method="post" action="<?= DOMAIN ?>controllers/porudzbine.php">
                            <input type="hidden" name="akcija" value="kupi">
                            <div class="form-group" style="margin-top:1rem;">
                                <label>Adresa za dostavu</label>
                                <input type="text" name="adresa" class="form-control" pattern="[A-Za-zČĆŽŠĐčćžšđ ]{3,}\s+\d+.*" oninvalid="this.setCustomValidity('Unesite ulicu i broj, npr. Knez Mihailova 12')" oninput="this.setCustomValidity('')" required>
                            </div>
                            <button type="submit" class="btn-checkout-now">Zavrsi kupovinu</button>
                        </form>
                    <?php } else { ?>
                        <a href="<?= DOMAIN ?>index.php?page=login" class="btn-checkout-now" style="display:block; text-align:center; text-decoration:none;">Prijavite se za kupovinu</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
