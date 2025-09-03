<?php
ob_start();
$title = "Onysis Boost • Dashboard";
$css = "/assets/css/style.css";
require VIEWS . 'components/navbar.php';

if (!isset($_SESSION['user'])) {
    header('Location: /login/');
    exit;
}

$username = $_SESSION['user']['username'] ?? '';
$userId = $_SESSION['user']['id_user'] ?? null;

// $data doit être passé par le contrôleur
// Filtrer les releases de l'utilisateur connecté
$userReleases = [];
if (isset($data) && is_array($data)) {
    foreach ($data as $release) {
        if ($release->getIdUser() == $userId) {
            $userReleases[] = $release;
        }
    }
}
?>

<?php if (count($userReleases) > 0): ?>
    <main>
        <section>
            <h2>Vos sorties</h2>
                <?php foreach ($userReleases as $release): ?>
                    <article class="release">
                        <?php if ($release->getCover()): ?>
                            <img src="<?= htmlspecialchars($release->getCover()); ?>" alt="Cover de <?= htmlspecialchars($release->getTitle()); ?>" style="max-width:150px;">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($release->getTitle()); ?></h3>
                        <p>Date de sortie : <?= htmlspecialchars($release->getReleaseDate()); ?></p>
                        <p>Type : <?= htmlspecialchars($release->getIdType()); ?></p>
                    </article>
                <?php endforeach; ?>
        </section>
        
        <a href="/new" class="new-release">Programmer une nouvelle sortie</a>
    </main>
<?php else: ?>
    <main class="no-releases">
        <h2>Vous n'avez aucune sortie programmée.</h2>
        <a href="/new" class="new-release">Programmer une sortie</a>
    </main>
<?php endif; ?>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';
require VIEWS . 'components/footer.php';