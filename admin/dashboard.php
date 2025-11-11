<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';

$counts = [
	'staff' => $mysqli->query("SELECT COUNT(*) c FROM staff")->fetch_assoc()['c'] ?? 0,
	'news' => $mysqli->query("SELECT COUNT(*) c FROM news")->fetch_assoc()['c'] ?? 0,
	'awards' => $mysqli->query("SELECT COUNT(*) c FROM achievements")->fetch_assoc()['c'] ?? 0,
	'notices' => $mysqli->query("SELECT COUNT(*) c FROM notices")->fetch_assoc()['c'] ?? 0,
	'complaints' => $mysqli->query("SELECT COUNT(*) c FROM complaints")->fetch_assoc()['c'] ?? 0
];
?>
<section class="section">
	<div class="container">
		<h2>Dashboard</h2>
		<div class="card-grid">
			<div class="card">
				<div class="content"><h3>Staff</h3><p>Total: <?= (int)$counts['staff'] ?></p></div>
			</div>
			<div class="card">
				<div class="content"><h3>News</h3><p>Total: <?= (int)$counts['news'] ?></p></div>
			</div>
			<div class="card">
				<div class="content"><h3>Awards</h3><p>Total: <?= (int)$counts['awards'] ?></p></div>
			</div>
			<div class="card">
				<div class="content"><h3>Notices</h3><p>Total: <?= (int)$counts['notices'] ?></p></div>
			</div>
			<div class="card">
				<div class="content"><h3>Complaints</h3><p>Total: <?= (int)$counts['complaints'] ?></p></div>
			</div>
		</div>
	</div>
	<div class="container" style="margin-top:16px;">
		<a class="btn" href="manage_staff.php">Manage Staff</a>
		<a class="btn" href="manage_news.php">Manage News</a>
		<a class="btn" href="manage_awards.php">Manage Awards</a>
		<a class="btn" href="manage_gallery.php">Manage Gallery</a>
		<a class="btn" href="manage_notices.php">Manage Notices</a>
		<a class="btn" href="manage_stats.php">Manage Statistics</a>
	</div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>


