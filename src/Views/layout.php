<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title; ?></title>
    <script src="https://kit.fontawesome.com/c1d0ab37d6.js" crossorigin="anonymous"></script>

    <?php if (isset($_SESSION['user'])): ?>
        <link rel="stylesheet" href="/assets/css/style.css">
    <?php endif; ?>

    <?php if (isset($css)): ?>
        <link rel="stylesheet" href="<?= $css ?>">
    <?php endif; ?>
</head>
<body>
    <?= $content; ?>
</body>
</html>
<?php
unset($_SESSION['error']);
unset($_SESSION['old']);