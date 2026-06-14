<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title; ?></title>
    <script src="https://kit.fontawesome.com/c1d0ab37d6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= $css ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/svg+xml" href="/assets/images/logo.svg">
    <meta name="description" content="Onysis Boost est une plateforme de promotion musicale conçue pour aider les artistes à maximiser leur visibilité et leur impact sur les plateformes de streaming. Grâce à des outils de planification, de suivi et d'analyse, Onysis Boost permet aux musiciens de gérer efficacement leurs campagnes de promotion et d'optimiser leurs résultats. Que vous soyez un artiste émergent ou établi, Onysis Boost vous offre les ressources nécessaires pour faire décoller votre carrière musicale.">
</head>
<body>
    <?= $content; ?>
</body>
</html>
<?php
unset($_SESSION['error']);
unset($_SESSION['old']);

