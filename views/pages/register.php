<div class="container">
    <div class="auth-box">
        <h2>Registracija</h2>

        <div id="form-error" class="inline-msg inline-msg-error" style="display:none;"></div>

        <form method="post" action="<?= DOMAIN ?>controllers/auth.php" novalidate onsubmit="return proveriRegister()">
            <input type="hidden" name="akcija" value="register">

            <div class="form-group">
                <label>Ime</label>
                <input type="text" name="ime" id="ime" class="form-control">
            </div>

            <div class="form-group">
                <label>Prezime</label>
                <input type="text" name="prezime" id="prezime" class="form-control">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" id="email" class="form-control">
            </div>

            <div class="form-group">
                <label>Lozinka</label>
                <input type="password" name="lozinka" id="lozinka" class="form-control">
            </div>

            <div class="form-group">
                <label>Ponovi lozinku</label>
                <input type="password" name="lozinka2" id="lozinka2" class="form-control">
            </div>

            <button type="submit" class="btn-submit-form" style="width:100%;">Registruj se</button>
        </form>

        <p style="margin-top:1rem; text-align:center; font-size:0.9rem;">
            Vec imate nalog? <a href="<?= DOMAIN ?>index.php?page=login" style="color:#9b2335;">Prijavite se</a>
        </p>
    </div>
</div>

<script>
    function prikaziGresku(poruka) {
        var greska = document.getElementById('form-error');
        greska.textContent = poruka;
        greska.style.display = 'block';
        return false;
    }

    function proveriRegister() {
        var ime = document.getElementById('ime').value.trim();
        var prezime = document.getElementById('prezime').value.trim();
        var email = document.getElementById('email').value.trim();
        var lozinka = document.getElementById('lozinka').value;
        var lozinka2 = document.getElementById('lozinka2').value;

        if (ime === '') {
            return prikaziGresku('Unesite ime');
        }

        if (prezime === '') {
            return prikaziGresku('Unesite prezime');
        }

        if (email === '') {
            return prikaziGresku('Unesite email adresu');
        }

        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(email)) {
            return prikaziGresku('Email adresa nije u ispravnom formatu');
        }

        if (lozinka.length < 6) {
            return prikaziGresku('Lozinka mora imati bar 6 karaktera');
        }

        if (lozinka !== lozinka2) {
            return prikaziGresku('Lozinke se ne poklapaju');
        }

        return true;
    }
</script>
