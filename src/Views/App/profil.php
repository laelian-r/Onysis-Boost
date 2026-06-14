<?php
ob_start();
$title = "Profil";
$css = "/assets/css/profil.css";

require VIEWS . 'components/navbar.php';


?>
    <div class="bg">
        <main>
            <h1>Bonjour <?= $_SESSION["user"]["username"] ?> !</h1>
            <p>Bienvenue sur votre page personnelle</p>
        </main>
    </div>

<?php
require VIEWS . 'components/footer.php';

$content = ob_get_clean();
require VIEWS . 'layout.php';