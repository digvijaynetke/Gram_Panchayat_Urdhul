<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$id = intval($_POST['id'] ?? 0);
	$name = trim($_POST['name'] ?? '');
	$position = trim($_POST['position'] ?? '');
	$phone = trim($_POST['phone'] ?? '');
	$photo = trim($_POST['photo'] ?? '');
	if ($id > 0) {
		$stmt = $mysqli->prepare("UPDATE staff SET name=?, position=?, phone=?, photo=? WHERE id=?");
		$stmt->bind_param('ssssi', $name, $position, $phone, $photo, $id);
		$stmt->execute();
		$msg = 'Updated.';
	} else {
		$stmt = $mysqli->prepare("INSERT INTO staff (name, position, phone, photo) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssss', $name, $position, $phone, $photo);
		$stmt->execute();
		$msg = 'Added.';
	}
}
if (isset($_GET['delete'])) {
	$id = intval($_GET['delete']);
	$mysqli->query("DELETE FROM staff WHERE id=$id");
	$msg = 'Deleted.';
}
$rows = $mysqli->query("SELECT * FROM staff ORDER BY id DESC");
?>
<section class="section">
	<div class="container">
		<h2>Manage Staff</h2>
		<?php if ($msg): ?><div class="form-success" style="display:block;"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
		<form method="post" style="margin-bottom:16px;">
			<div class="form-row"><label>Name</label><input name="name" required></div>
			<div class="form-row"><label>Position</label><input name="position" required></div>
			<div class="form-row"><label>Phone</label><input name="phone"></div>
			<div class="form-row"><label>Photo (path in assets/images/staff)</label><input name="photo" placeholder="assets/images/staff/xyz.jpg"></div>
			<div class="form-actions"><button class="btn btn-accent">Add</button></div>
		</form>
		<div class="table-responsive">
			<table>
				<thead><tr><th>ID</th><th>Name</th><th>Position</th><th>Phone</th><th>Photo</th><th>Actions</th></tr></thead>
				<tbody>
				<?php while($r = $rows->fetch_assoc()): ?>
					<tr>
						<td><?= (int)$r['id'] ?></td>
						<td><?= htmlspecialchars($r['name']) ?></td>
						<td><?= htmlspecialchars($r['position']) ?></td>
						<td><?= htmlspecialchars($r['phone']) ?></td>
						<td><?php if($r['photo']): ?><img src="../<?= htmlspecialchars($r['photo']) ?>" alt="" style="height:40px;"><?php endif; ?></td>
						<td><a class="btn" href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
					</tr>
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>


