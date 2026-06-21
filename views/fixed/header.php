<!doctype html>
<html lang="sr">
<?php require_once "views/fixed/head.php"; ?>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= DOMAIN ?>index.php?page=home">
            ReadMore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= $page == "home" ? "active" : "" ?>" href="<?= DOMAIN ?>index.php?page=home">Pocetna</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page == "books" ? "active" : "" ?>" href="<?= DOMAIN ?>index.php?page=books">Knjige</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page == "about" ? "active" : "" ?>" href="<?= DOMAIN ?>index.php?page=about">Autor</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= DOMAIN ?>dokumentacija.pdf" target="_blank">Dokumentacija</a>
                </li>
                <?php if(isLoggedIn()) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == "orders" ? "active" : "" ?>" href="<?= DOMAIN ?>index.php?page=orders">Porudzbine</a>
                    </li>
                <?php } ?>
                <?php if(isAdmin()) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == "admin" ? "active" : "" ?>" href="<?= DOMAIN ?>index.php?page=admin">Admin</a>
                    </li>
                <?php } ?>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if(isLoggedIn()) { ?>
                    <span style="color:#c4a882; font-size:0.85rem;">Zdravo, <?= user()->ime ?></span>
                    <a href="<?= DOMAIN ?>controllers/auth.php?out=1" class="nav-cart-btn" onclick="event.preventDefault();document.getElementById('logoutForm').submit();">Odjava</a>
                    <form id="logoutForm" method="post" action="<?= DOMAIN ?>controllers/auth.php" style="display:none;">
                        <input type="hidden" name="akcija" value="logout">
                    </form>
                <?php } else { ?>
                    <a class="nav-link <?= $page == "login" ? "active" : "" ?>" href="<?= DOMAIN ?>index.php?page=login">Prijava</a>
                    <a class="nav-link <?= $page == "register" ? "active" : "" ?>" href="<?= DOMAIN ?>index.php?page=register">Registracija</a>
                <?php } ?>

                <a href="<?= DOMAIN ?>index.php?page=cart" class="nav-cart-btn <?= $page == "cart" ? "active" : "" ?>">
                    Korpa
                    <span class="cart-badge"><?= isset($_SESSION["korpa"]) ? array_sum($_SESSION["korpa"]) : 0 ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container" style="margin-top:1rem;">
    <?php if(hasFlash("poruka")) { ?>
        <div class="inline-msg inline-msg-success"><?= getFlash("poruka") ?></div>
    <?php } ?>
    <?php if(hasFlash("greska")) { ?>
        <div class="inline-msg inline-msg-error"><?= getFlash("greska") ?></div>
    <?php } ?>
</div>
