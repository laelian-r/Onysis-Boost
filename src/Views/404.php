<?php
ob_start();
$css = '/assets/css/error.css';
$title = "Erreur 404 - Page non trouvée";
?>

<main>
    <div class="text">
        <h1>Erreur 404</h1>
        <p>Il semble que cette page n’existe pas. <br> Essayez une autre URL puis réessayez.</p>
    </div>
    <a href="/">Revenir à la page d'accueil</a>
</main>

<?php

$content = ob_get_clean();
require VIEWS . 'layout.php';
