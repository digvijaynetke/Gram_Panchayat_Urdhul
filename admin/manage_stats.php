<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$metric = trim($_POST['metric'] ?? '');
	$value = trim($_POST['value'] ?? '');
	$stmt = $mysqli->prepare("INSERT INTO statistics (metric, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value=VALUES(value)");
	$stmt->bind_param('ss', $metric, $value);
	$stmt->execute();
	$msg = 'Saved.';
}
if (isset($_GET['delete'])) {
	$id = intval($_GET['delete']);
	$mysqli->query("DELETE FROM statistics WHERE id=$id");
	$msg = 'Deleted.';
}
$rows = $mysqli->query("SELECT * FROM statistics ORDER BY id DESC");
?>
<section class="section">
	<div class="container">
		<h2>Manage Statistics</h2>
		<?php if ($msg): ?><div class="form-success" style="display:block;"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
		<form method="post" style="margin-bottom:16px;">
			<div class="form-row"><label>Metric</label><input name="metric" required></div>
			<div class="form-row"><label>Value</label><input name="value" required></div>
			<div class="form-actions"><button class="btn btn-accent">Save</button></div>
		</form>
		<div class="table-responsive">
			<table>
				<thead><tr><th>ID</th><th>Metric</th><th>Value</th><th>Actions</th></tr></thead>
				<tbody>
				<?php while($r = $rows->fetch_assoc()): ?>
					<tr>
						<td><?= (int)$r['id'] ?></td>
						<td><?= htmlspecialchars($r['metric']) ?></td>
						<td><?= htmlspecialchars($r['value']) ?></td>
						<td><a class="btn" href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
					</tr>
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>


