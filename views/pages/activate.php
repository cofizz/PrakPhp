<?php
$kod = get("kod");
?>

<div class="container">
    <div class="auth-box" style="text-align:center;">
        <h2>Aktivacija naloga</h2>

        <?php if(!$kod) { ?>
            <p style="color:#9b2335;">Nedostaje aktivacioni kod.</p>
        <?php } else { ?>
            <p>Kliknite na dugme da biste aktivirali svoj nalog.</p>
            <form method="post" action="<?= DOMAIN ?>controllers/auth.php">
                <input type="hidden" name="akcija" value="activate">
                <input type="hidden" name="kod" value="<?= htmlspecialchars($kod) ?>">
                <button type="submit" class="btn-primary-custom">Aktiviraj nalog</button>
            </form>
        <?php } ?>
    </div>
</div>
