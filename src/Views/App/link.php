<?php
ob_start();
$css = "/assets/css/style.css";
require VIEWS . 'components/navbar.php';
?>

<h1>Lien</h1>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';
require VIEWS . 'components/footer.php';