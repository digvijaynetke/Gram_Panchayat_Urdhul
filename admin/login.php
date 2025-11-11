<?php
session_start();
require __DIR__ . '/../includes/db_connect.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');
	if ($username === '' || $password === '') {
		$error = 'Please enter username and password.';
	} else {
		$stmt = $mysqli->prepare("SELECT id, username, password_hash FROM admin WHERE username = ?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($row = $res->fetch_assoc()) {
			if (password_verify($password, $row['password_hash'])) {
				$_SESSION['admin_id'] = $row['id'];
				$_SESSION['admin_username'] = $row['username'];
				header('Location: dashboard.php');
				exit;
			}
		}
		$error = 'Invalid credentials.';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Login</title>
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
	<section class="section">
		<div class="container" style="max-width:420px;">
			<h2>Admin Login</h2>
			<?php if ($error): ?>
				<div class="form-error" style="display:block;"><?= htmlspecialchars($error) ?></div>
			<?php endif; ?>
			<form method="post">
				<div class="form-row">
					<label for="username">Username</label>
					<input id="username" type="text" name="username" required>
				</div>
				<div class="form-row">
					<label for="password">Password</label>
					<input id="password" type="password" name="password" required>
				</div>
				<div class="form-actions">
					<button class="btn btn-accent" type="submit">Login</button>
				</div>
			</form>
			<p style="color:#6b7280;margin-top:10px;">Tip: Add an admin user in database.sql and change password.</p>
		</div>
	</section>
</body>
</html>


