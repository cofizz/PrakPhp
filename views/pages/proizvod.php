<?php
$id = (int)get("id");
$knjiga = queryOne("SELECT p.*, k.naziv AS kategorija FROM proizvodi p JOIN kategorije k ON k.id = p.kategorija_id WHERE p.id = :id", [":id" => $id]);

if(!$knjiga) {
    echo "<div class='container' style='padding:4rem 0;'><h2>Knjiga nije pronadjena.</h2></div>";
    require_once "views/fixed/footer.php";
    exit;
}
?>

<div class="container" style="padding:3rem 0;">
    <a href="<?= DOMAIN ?>index.php?page=books" class="continue-link" style="text-align:left; margin-bottom:1rem;">&larr; Nazad na sve knjige</a>

    <div class="row g-5 mt-1">
        <div class="col-md-4">
            <img src="<?= slikaUrl($knjiga->slika) ?>" alt="<?= $knjiga->naziv ?>" class="product-detail-img">
        </div>
        <div class="col-md-8">
            <span class="book-genre"><?= $knjiga->kategorija ?></span>
            <h1 class="author-name" style="font-size:2.2rem;"><?= $knjiga->naziv ?></h1>
            <p class="book-author" style="font-size:1.1rem;"><?= $knjiga->autor ?></p>

            <div class="book-meta" style="margin-bottom:1rem;">
                <span class="stars"><?= $knjiga->ocena ?>⭐</span>
                <span class="review-count">(<?= number_format($knjiga->broj_recenzija) ?> recenzija)</span>
            </div>

            <p class="author-bio"><?= $knjiga->opis ?></p>

            <div class="row g-3 mt-2 mb-4">
                <div class="col-6 col-sm-3">
                    <div class="info-card">
                        <div class="info-card-label">Godina</div>
                        <div class="info-card-value"><?= $knjiga->godina ?></div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="info-card">
                        <div class="info-card-label">Strane</div>
                        <div class="info-card-value"><?= $knjiga->strane ?></div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="price-now" style="font-size:2rem;">$<?= number_format($knjiga->cena, 2) ?></span>
                <?php if($knjiga->stara_cena) { ?>
                    <span class="price-old" style="font-size:1.1rem;">$<?= number_format($knjiga->stara_cena, 2) ?></span>
                <?php } ?>
            </div>

            <a class="btn-primary-custom mt-3" href="<?= DOMAIN ?>controllers/korpa.php?akcija=dodaj&id=<?= $knjiga->id ?>">Dodaj u korpu</a>
        </div>
    </div>
</div>
