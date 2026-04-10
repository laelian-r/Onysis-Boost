<?php
ob_start();
$title = "Ajouter une release";
$css = "/assets/css/newRelease.css";
require VIEWS . 'components/navbar.php';
?>

<main>
    <section>
        <form method="post">
            <h2>Ajouter une nouvelle sortie</h2>
            <?php if (!empty($error)): ?>
                <p style="color:red"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            
            <div class="blockInput">
                <label>Titre :</label>
                <input type="text" name="title" required>
            </div>
            <div class="blockInput">
                <label>Type :</label>
                <select name="id_type" required>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type['id_type'] ?>"><?= htmlspecialchars($type['type']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="blockInput">
                <label>Nombre de morceaux:</label>
                <input type="number" name="number_songs" min="1" required>
            </div>
            <div class="blockInput">
                <label>Date de sortie :</label>
                <input type="date" name="release_date" required>
            </div>
            <div class="blockInput">
                <label>Budget:</label>
                <input type="number" name="budget" min="0" required>
            </div>
            <div class="blockInput">
                <label>Détails:</label>
                <textarea name="details" required></textarea>
            </div>
            <button type="submit">Ajouter</button>
        </form>
    </section>
</main>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';
require VIEWS . 'components/footer.php';