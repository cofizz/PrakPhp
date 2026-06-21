<?php
$kategorije = getAll("kategorije");
$pocetnaKat = get("kategorija");
?>

<div class="page-banner">
    <div class="container">
        <h1>Sve knjige</h1>
        <p class="mt-1">Pregledajte celu kolekciju - pronadjite svoju sledecu knjigu.</p>
    </div>
</div>

<div class="container" style="padding-top:2.5rem; padding-bottom:3rem;">

    <div class="filter-panel">
        <div style="flex:1; min-width:200px;">
            <input type="text" id="search-box" class="form-control" placeholder="Pretraga po nazivu ili autoru...">
        </div>

        <select id="genre-select" class="form-select" style="min-width:155px;">
            <option value="">Svi zanrovi</option>
            <?php foreach($kategorije as $k) { ?>
                <option value="<?= $k->id ?>"><?= $k->naziv ?></option>
            <?php } ?>
        </select>

        <select id="sort-select" class="form-select" style="min-width:175px;">
            <option value="">Sortiranje</option>
            <option value="price-low">Cena rastuce</option>
            <option value="price-high">Cena opadajuce</option>
            <option value="rating">Ocena</option>
            <option value="title">Naziv A-Z</option>
            <option value="year-new">Najnovije</option>
            <option value="year-old">Najstarije</option>
        </select>

        <button class="btn-reset" onclick="resetAll()">X Ponisti</button>
    </div>

    <div id="loading-area" class="loading-spinner">Ucitavanje knjiga...</div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4" id="books-grid"></div>

    <div id="no-results" class="no-results-msg" style="display:none;">
        <h3>Nema rezultata</h3>
        <p>Probajte druge filtere!</p>
    </div>

    <nav class="blog-pagination" id="pagination" style="text-align:center; padding-top:2rem;"></nav>
</div>

<script>
    var trenutnaStrana = 1;

    function ucitajKnjige() {
        var search = document.getElementById('search-box').value;
        var kategorija = document.getElementById('genre-select').value;
        var sort = document.getElementById('sort-select').value;

        var loading = document.getElementById('loading-area');
        loading.style.display = 'block';

        var url = '<?= DOMAIN ?>models/getProizvodi.php?strana=' + trenutnaStrana
            + '&search=' + encodeURIComponent(search)
            + '&kategorija=' + encodeURIComponent(kategorija)
            + '&sort=' + encodeURIComponent(sort);

        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                loading.style.display = 'none';
                prikaziKnjige(data);
            })
            .catch(function(e) {
                loading.style.display = 'none';
                console.log('Greska:', e);
            });
    }

    function prikaziKnjige(data) {
        var grid = document.getElementById('books-grid');
        var noRes = document.getElementById('no-results');

        if (data.proizvodi.length === 0) {
            grid.innerHTML = '';
            noRes.style.display = 'block';
            document.getElementById('pagination').innerHTML = '';
            return;
        }

        noRes.style.display = 'none';

        var html = '';
        for (var i = 0; i < data.proizvodi.length; i++) {
            html += karticaKnjige(data.proizvodi[i]);
        }
        grid.innerHTML = html;

        prikaziPaginaciju(data);
    }

    function karticaKnjige(b) {
        var slika = b.thumbnail;
        if (!slika) {
            slika = 'https://placehold.co/300x420/5c3d2e/f5e6c8?text=No+Cover';
        } else if (slika.substring(0, 4) !== 'http') {
            slika = '<?= DOMAIN ?>' + slika;
        }

        var staraCena = '';
        if (b.stara_cena) {
            staraCena = '<span class="price-old">$' + parseFloat(b.stara_cena).toFixed(2) + '</span>';
        }

        return '<div class="col">'
            + '<div class="book-card">'
            + '<div class="book-cover-wrap">'
            + '<a href="<?= DOMAIN ?>index.php?page=proizvod&id=' + b.id + '">'
            + '<img src="' + slika + '" class="book-cover" alt="' + b.naziv + '">'
            + '</a></div>'
            + '<div class="book-info">'
            + '<span class="book-genre">' + b.kategorija + '</span>'
            + '<h5 class="book-title">' + b.naziv + '</h5>'
            + '<p class="book-author">' + b.autor + '</p>'
            + '<div class="book-meta"><span class="stars">' + b.ocena + '⭐</span>'
            + '<span class="review-count">(' + b.broj_recenzija + ')</span></div>'
            + '<div class="book-footer"><div class="price-wrap">'
            + '<span class="price-now">$' + parseFloat(b.cena).toFixed(2) + '</span>' + staraCena
            + '</div>'
            + '<a class="btn-cart" href="<?= DOMAIN ?>controllers/korpa.php?akcija=dodaj&id=' + b.id + '">Dodaj u korpu</a>'
            + '</div></div></div></div>';
    }

    function prikaziPaginaciju(data) {
        var pag = document.getElementById('pagination');
        if (data.brojStrana <= 1) {
            pag.innerHTML = '';
            return;
        }

        var html = '';
        for (var i = 1; i <= data.brojStrana; i++) {
            if (i === data.strana) {
                html += '<a class="btn btn-outline-secondary disabled" href="#">' + i + '</a> ';
            } else {
                html += '<a class="btn btn-outline-primary" href="#" onclick="idiNaStranu(' + i + ');return false;">' + i + '</a> ';
            }
        }
        pag.innerHTML = html;
    }

    function idiNaStranu(s) {
        trenutnaStrana = s;
        ucitajKnjige();
        window.scrollTo(0, 0);
    }

    function resetAll() {
        document.getElementById('search-box').value = '';
        document.getElementById('genre-select').value = '';
        document.getElementById('sort-select').value = '';
        trenutnaStrana = 1;
        ucitajKnjige();
    }

    document.getElementById('search-box').addEventListener('input', function() {
        trenutnaStrana = 1;
        ucitajKnjige();
    });
    document.getElementById('genre-select').addEventListener('change', function() {
        trenutnaStrana = 1;
        ucitajKnjige();
    });
    document.getElementById('sort-select').addEventListener('change', function() {
        trenutnaStrana = 1;
        ucitajKnjige();
    });

    <?php if($pocetnaKat) { ?>
        document.getElementById('genre-select').value = '<?= (int)$pocetnaKat ?>';
    <?php } ?>

    ucitajKnjige();
</script>
