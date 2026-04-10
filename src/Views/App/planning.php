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
            <h2><?= htmlspecialchars($data['title'] ?? ''); ?></h2>
            <p>Artiste : <?= htmlspecialchars($data['username'] ?? ''); ?></p>
            <p>Date de sortie : <?= htmlspecialchars($data['release_date'] ?? ''); ?></p>
        </section>
    <?php endif; ?>

    <table>
        <thead>
			<tr>
				<th>Jour</th>
				<th>Action</th>
				<th>Canal</th>
				<th>Budget estimé</th>
			</tr>
		</thead>
		<tbody id="tbody">
            <tr>
                <td>Lundi 10 septembre</td>
                <td>Poster une story sur intagram</td>
                <td>Instagram</td>
                <td>0 €</td>
            </tr>
        </tbody>
    </table>
</main>

<?php
$content = ob_get_clean();
require VIEWS . 'layout.php';