<div class="container">
    <div class="auth-box">
        <h2>Prijava</h2>

        <div id="form-error" class="inline-msg inline-msg-error" style="display:none;"></div>

        <form method="post" action="<?= DOMAIN ?>controllers/auth.php" novalidate onsubmit="return proveriLogin()">
            <input type="hidden" name="akcija" value="login">

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" id="email" class="form-control">
            </div>

            <div class="form-group">
                <label>Lozinka</label>
                <input type="password" name="lozinka" id="lozinka" class="form-control">
            </div>

            <button type="submit" class="btn-submit-form" style="width:100%;">Prijavi se</button>
        </form>

        <p style="margin-top:1rem; text-align:center; font-size:0.9rem;">
            Nemate nalog? <a href="<?= DOMAIN ?>index.php?page=register" style="color:#9b2335;">Registrujte se</a>
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

    function proveriLogin() {
        var email = document.getElementById('email').value.trim();
        var lozinka = document.getElementById('lozinka').value;

        if (email === '') {
            return prikaziGresku('Unesite email adresu');
        }

        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(email)) {
            return prikaziGresku('Email adresa nije u ispravnom formatu');
        }

        if (lozinka === '') {
            return prikaziGresku('Unesite lozinku');
        }

        return true;
    }
</script>
