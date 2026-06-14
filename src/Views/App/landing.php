<?php
ob_start();
$title = "Onysis Boost";
$css = "/assets/css/landing.css";

if (isset($_SESSION["user"])) {
    header("Location: /dashboard");
    exit();
}
?>

    <div class="bg">
        <main>
            <img src="/assets/images/logo.svg" alt="Onysis Boost">
            <h1>Ønysis Boost</h1>
            <p>Plannifiez la promotion de votre dérnière sortie gratuitement de façon simple et automatisé</p>
            
            <ul>
                <li>
                    <a href="/login">Se connecter</a>
                </li>
                <li>
                    <a href="/register" class="signup">🚀 Commencer</a>
                </li>
            </ul>
        </main>
    </div>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';