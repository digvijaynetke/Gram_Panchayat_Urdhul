<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$title = trim($_POST['title'] ?? '');
	$content = trim($_POST['content'] ?? '');
	$date = trim($_POST['date'] ?? date('Y-m-d'));
	$attachment = trim($_POST['attachment'] ?? '');
	$stmt = $mysqli->prepare("INSERT INTO notices (title, content, date, attachment) VALUES (?, ?, ?, ?)");
	$stmt->bind_param('ssss', $title, $content, $date, $attachment);
	$stmt->execute();
	$msg = 'Added.';
}
if (isset($_GET['delete'])) {
	$id = intval($_GET['delete']);
	$mysqli->query("DELETE FROM notices WHERE id=$id");
	$msg = 'Deleted.';
}
$rows = $mysqli->query("SELECT * FROM notices ORDER BY date DESC, id DESC");
?>
<section class="section">
	<div class="container">
		<h2>Manage Notices</h2>
		<?php if ($msg): ?><div class="form-success" style="display:block;"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
		<form method="post" style="margin-bottom:16px;">
			<div class="form-row"><label>Title</label><input name="title" required></div>
			<div class="form-row"><label>Content</label><textarea name="content" rows="3" required></textarea></div>
			<div class="form-row"><label>Date</label><input name="date" type="date" value="<?= date('Y-m-d') ?>"></div>
			<div class="form-row"><label>Attachment (path)</label><input name="attachment" placeholder="assets/docs/file.pdf"></div>
			<div class="form-actions"><button class="btn btn-accent">Add</button></div>
		</form>
		<div class="table-responsive">
			<table>
				<thead><tr><th>ID</th><th>Title</th><th>Date</th><th>Attachment</th><th>Actions</th></tr></thead>
				<tbody>
				<?php while($r = $rows->fetch_assoc()): ?>
					<tr>
						<td><?= (int)$r['id'] ?></td>
						<td><?= htmlspecialchars($r['title']) ?></td>
						<td><?= htmlspecialchars($r['date']) ?></td>
						<td><?php if($r['attachment']): ?><a href="../<?= htmlspecialchars($r['attachment']) ?>" target="_blank">Open</a><?php endif; ?></td>
						<td><a class="btn" href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
					</tr>
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>


