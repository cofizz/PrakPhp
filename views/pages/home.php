<?php
$kategorije = getAll("kategorije");
$topKnjige = query("SELECT p.*, k.naziv AS kategorija FROM proizvodi p JOIN kategorije k ON k.id = p.kategorija_id ORDER BY p.ocena DESC LIMIT 4");
?>

<section class="hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 hero-content">
                <h1>Pronadji svoju<br><em>sledecu knjigu</em></h1>
                <p>Klasici, trileri, istorijski epovi, svetovi fantastike i jos mnogo toga - rucno odabrane knjige za svakog citaoca.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?= DOMAIN ?>index.php?page=books" class="btn-primary-custom">Sve knjige</a>
                    <a href="<?= DOMAIN ?>index.php?page=books&kategorija=1" class="btn-secondary-custom">Klasici</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="genres-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Svaki zanr prica drugaciju pricu</h2>
        </div>

        <div class="row g-3 mt-3">
            <?php foreach($kategorije as $k) {
                $broj = queryOne("SELECT COUNT(*) AS broj FROM proizvodi WHERE kategorija_id = :id", [":id" => $k->id]);
            ?>
                <div class="col-6 col-sm-4 col-md-2">
                    <a href="<?= DOMAIN ?>index.php?page=books&kategorija=<?= $k->id ?>" class="genre-card">
                        <span class="genre-name"><?= $k->naziv ?></span>
                        <span class="genre-count"><?= $broj->broj ?> knjige</span>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="books-section">
    <div class="container">
        <div class="section-header mb-4">
            <h2 class="section-title mb-0">Najbolje ocenjene knjige</h2>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach($topKnjige as $b) { ?>
                <div class="col">
                    <div class="book-card">
                        <div class="book-cover-wrap">
                            <a href="<?= DOMAIN ?>index.php?page=proizvod&id=<?= $b->id ?>">
                                <img src="<?= slikaUrl($b->thumbnail) ?>" alt="<?= $b->naziv ?>" class="book-cover">
                            </a>
                        </div>
                        <div class="book-info">
                            <span class="book-genre"><?= $b->kategorija ?></span>
                            <h5 class="book-title"><?= $b->naziv ?></h5>
                            <p class="book-author"><?= $b->autor ?></p>
                            <div class="book-meta">
                                <span class="stars"><?= $b->ocena ?>⭐</span>
                                <span class="review-count">(<?= number_format($b->broj_recenzija) ?>)</span>
                            </div>
                            <div class="book-footer">
                                <div class="price-wrap">
                                    <span class="price-now">$<?= number_format($b->cena, 2) ?></span>
                                </div>
                                <a class="btn-cart" href="<?= DOMAIN ?>controllers/korpa.php?akcija=dodaj&id=<?= $b->id ?>">Dodaj u korpu</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
