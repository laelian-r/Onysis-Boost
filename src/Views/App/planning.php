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
    <h1>Planning</h1>

    <?php if (isset($data) && $data): ?>
        <section class="release-info">
            <h2><?= htmlspecialchars($data['title'] ?? ''); ?></h2>
            <p>Artiste : <?= htmlspecialchars($data['username'] ?? ''); ?></p>
            <p>Date de sortie : <?= htmlspecialchars($data['release_date'] ?? ''); ?></p>
        </section>
    <?php endif; ?>

    <?php if (!empty($planning)): ?>
        <section class="ai-planning">
            <h3>Planning généré (Markdown)</h3>
            <div class="planning-markdown" style="white-space:pre-wrap; background:#f8f8f8; padding:1rem; border-radius:6px;">
                <?= nl2br(htmlspecialchars($planning)); ?>
            </div>
        </section>
    <?php else: ?>
        <p>Aucun planning disponible pour cette sortie.</p>
    <?php endif; ?>
</main>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';