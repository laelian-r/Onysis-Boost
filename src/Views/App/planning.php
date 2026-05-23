<?php
ob_start();
$title = "Onysis Boost";
$css = "/assets/css/planning.css";
require VIEWS . 'components/navbar.php';

if (!isset($_SESSION["user"])) {
    header("Location: /login");
    exit();
}
?>

<main>
    <?php if (isset($data) && $data): ?>
        <section class="release-info">
            <div class="title">
                <a href="/" class="arrow-left"><i class="fa-solid fa-arrow-left"></i></a>
                <h2><?= htmlspecialchars($data['title'] ?? ''); ?></h2>
            </div>
            <p>Artiste : <span><?= htmlspecialchars($data['username'] ?? ''); ?></span></p>
            <p>Date de sortie : <span><?= date('d/m/Y', strtotime($data['release_date'] ?? '')); ?></span></p>
        </section>
    <?php endif; ?>

    <div class="info">
        <p>💡 Les budgets affichés sont des estimations indicatives. Vérifie les tarifs réels sur chaque plateforme avant de te lancer.</p>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th class="day" scope="col">Jour</th>
                    <th class="action" scope="col">Action</th>
                    <th class="budget" scope="col">Budget estimé</th>
                </tr>
            </thead>
            <tbody id="tbody">
                <?php if (!empty($planningData)): ?>
                    <?php foreach ($planningData as $entry): ?>
                        <tr>
                            <td class="day-body">
                                <?= htmlspecialchars($entry['jour']) ?>
                            </td>
                            <td class="action-body">
                                <ul>
                                    <?php foreach ($entry['actions'] ?? [] as $action): ?>
                                        <li>
                                            <span class="badge"><?= htmlspecialchars($action['canal'] ?? '') ?></span>
                                            <?= htmlspecialchars($action['action'] ?? '') ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td class="budget-body">
                                <?= htmlspecialchars($entry['budget_estime'] ?? '') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="content">
                        <td colspan="4">Aucune donnée disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';