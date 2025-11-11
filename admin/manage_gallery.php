<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = trim($_POST['name'] ?? '');
	$description = trim($_POST['description'] ?? '');
	$image = trim($_POST['image'] ?? '');
	$stmt = $mysqli->prepare("INSERT INTO tourist_places (name, description, image) VALUES (?, ?, ?)");
	$stmt->bind_param('sss', $name, $description, $image);
	$stmt->execute();
	$msg = 'Added.';
}
if (isset($_GET['delete'])) {
	$id = intval($_GET['delete']);
	$mysqli->query("DELETE FROM tourist_places WHERE id=$id");
	$msg = 'Deleted.';
}
$rows = $mysqli->query("SELECT * FROM tourist_places ORDER BY id DESC");
?>
<section class="section">
	<div class="container">
		<h2>Manage Gallery / Tourist Places</h2>
		<?php if ($msg): ?><div class="form-success" style="display:block;"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
		<form method="post" style="margin-bottom:16px;">
			<div class="form-row"><label>Name</label><input name="name" required></div>
			<div class="form-row"><label>Description</label><textarea name="description" rows="3"></textarea></div>
			<div class="form-row"><label>Image (path in assets/images/gallery)</label><input name="image" placeholder="assets/images/gallery/abc.jpg"></div>
			<div class="form-actions"><button class="btn btn-accent">Add</button></div>
		</form>
		<div class="table-responsive">
			<table>
				<thead><tr><th>ID</th><th>Name</th><th>Image</th><th>Actions</th></tr></thead>
				<tbody>
				<?php while($r = $rows->fetch_assoc()): ?>
					<tr>
						<td><?= (int)$r['id'] ?></td>
						<td><?= htmlspecialchars($r['name']) ?></td>
						<td><?php if($r['image']): ?><img src="../<?= htmlspecialchars($r['image']) ?>" alt="" style="height:40px;"><?php endif; ?></td>
						<td><a class="btn" href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
					</tr>
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>


