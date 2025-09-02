<?php
ob_start();
$title = "Onysis Boost · Accueil";
$css = "/assets/css/style.css";
require VIEWS . 'components/navbar.php';

if (!isset($_SESSION['user'])) {
    header('Location: /login/');
}

$username = $_SESSION['user']['username'] ?? header('Location: /login/');
?>

    <h1>Ønysis Boost</h1>
    <p>Bienvenue <?= htmlspecialchars($username); ?></p>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';
require VIEWS . 'components/footer.php';