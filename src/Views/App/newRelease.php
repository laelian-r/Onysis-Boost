<?php
ob_start();
$title = "Ajouter une release";
$css = "/assets/css/newRelease.css";
require VIEWS . 'components/navbar.php';
?>

<main>
    <section>
        <h2>Ajouter une nouvelle release</h2>
        <?php if (!empty($error)): ?>
            <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="blockInput">
                <label>Titre :</label>
                <input type="text" name="title" required>
            </div>
            <div class="blockInput">
                <label>Cover:</label>
                <input type="file" name="cover" required>
            </div>
            <div class="blockInput">
                <label>Date de sortie :</label>
                <input type="date" name="release_date" required>
            </div>
            <div class="blockInput">
                <label>Type :</label>
                <select name="id_type" required>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type['id_type'] ?>"><?= htmlspecialchars($type['type']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Ajouter</button>
        </form>
    </section>
</main>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';
require VIEWS . 'components/footer.php';