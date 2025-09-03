<?php
ob_start();

if (!isset($_SESSION['user'])) {
    header('Location: /login/');
}

$username = $_SESSION['user']['username'] ?? header('Location: /login/');
?>
<head>
    <link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer" 
    />
</head>

<header>
    <nav>
        <a href="/dashboard"><h1>Ønysis Boost</h1></a>
        <!-- <ul>
            <li>
                <a href="/dashboard" class="hidden">Dashboard</a>
            </li>
        </ul> -->
        <div class="dropdown-container">
            <div class="link-icon-container">
                <div class="profile-picture-container">
                    <!-- image de profil -->
                    <p><?= htmlspecialchars($username); ?></p>
                </div>
                <i class="fa-solid fa-caret-down"></i>
            </div>
            <div class="dropdown-content">
                <a href="#"><i class="fa-solid fa-user"></i>Profil</a>
                <a href="#"><i class="fa-solid fa-gear"></i>Paramètres</a>
                <a href="/logout"><i class="fa-solid fa-power-off"></i>Déconnexion</a>
            </div>
        </div>
    </nav>
</header>