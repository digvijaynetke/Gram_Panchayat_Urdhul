<?php
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo json_encode(['ok'=>false,'message'=>'Invalid request']);
	exit;
}
require __DIR__ . '/db_connect.php';
$cfg = require __DIR__ . '/config.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
	echo json_encode(['ok'=>false,'message'=>'Email and complaint are required.']);
	exit;
}
$stmt = $mysqli->prepare("INSERT INTO complaints (name, email, message, date) VALUES (?, ?, ?, NOW())");
$stmt->bind_param('sss', $name, $email, $message);
$ok = $stmt->execute();
if (!$ok) {
	echo json_encode(['ok'=>false,'message'=>'Failed to save.']);
	exit;
}

// Send email (simple mail(); configure server later)
$to = $cfg['site']['admin_email'];
$subject = 'New Complaint Submitted';
$body = "Name: " . ($name ?: 'N/A') . "\nEmail: $email\n\nMessage:\n$message\n";
@mail($to, $subject, $body, 'From: '.$cfg['site']['from_email']);

// Acknowledge to complainant
@mail($email, 'Complaint Received - Gram Panchayat Urdhul', "Thank you for your complaint. We have received it and will act on it.\n\nCopy of your complaint:\n".$message, 'From: '.$cfg['site']['from_email']);

echo json_encode(['ok'=>true,'message'=>'Complaint submitted. We have sent a confirmation to your email.']);


