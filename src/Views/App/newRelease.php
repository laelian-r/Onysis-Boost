<?php
ob_start();
$title = "Ajouter une release";
$css = "/assets/css/style.css";
require VIEWS . 'components/navbar.php';
?>

<main>
    <h2>Ajouter une nouvelle release</h2>
    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Titre : <input type="text" name="title" required></label><br>
        <label>Cover : <input type="file" name="cover" required></label><br>
        <label>Date de sortie : <input type="date" name="release_date" required></label><br>
        <label>Type :
            <select name="id_type" required>
                <?php foreach ($types as $type): ?>
                    <option value="<?= $type['id_type'] ?>"><?= htmlspecialchars($type['type']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">Ajouter</button>
    </form>
</main>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';
require VIEWS . 'components/footer.php';