<?php
ob_start();
$title = "Onysis Boost • Dashboard";
$css = "/assets/css/dashboard.css";
require VIEWS . 'components/navbar.php';

if (!isset($_SESSION['user'])) {
    header('Location: /login/');
    exit();
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
                <?php foreach ($userReleases as $release): ?>
                    <article>
                        <h3><?= htmlspecialchars($release->getTitle()); ?></h3>
                        
                        <?php
                        $typeNames = [];
                        if (isset($types) && is_array($types)) {
                            foreach ($types as $type) {
                                $typeNames[$type['id_type']] = $type['type'];
                            }
                        }
                        ?>
                        <p>
                            <?php
                                $date = new DateTime($release->getReleaseDate());
                                echo htmlspecialchars($date->format('d/m/Y'));
                            ?>
                        </p>
                        <p><?= isset($typeNames[$release->getIdType()]) ? htmlspecialchars($typeNames[$release->getIdType()]) : 'Type inconnu'; ?></p>

                        <div class="statut">
                            <?php
                            // Si $release->getReleaseDate() est déjà un objet DateTime
                            $releaseDate = is_string($release->getReleaseDate())
                                ? new DateTime($release->getReleaseDate())
                                : $release->getReleaseDate();

                            $currentDate = new DateTime();

                            if ($releaseDate < $currentDate) {
                                echo '<p class="completed">Terminé</p>';
                            } else {
                                echo '<p class="process">En cours</p>';
                            }
                            ?>
                        </div>
                        
                        <div class="buttons">
                            <a href="/planning/<?= htmlspecialchars($release->getIdRelease()); ?>" class="link-planning">Planning</a>
                            <a href="/dashboard/delete/<?= htmlspecialchars($release->getIdRelease()); ?>" class="delete">Supprimer</a>
                        </div>
                    </article>
                <?php endforeach; ?>
        </section>
        
        <div class="container">
            <a href="/new" class="new-release">Programmer une nouvelle sortie</a>
        </div>
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