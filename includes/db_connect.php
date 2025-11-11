<?php
$cfg = require __DIR__ . '/config.php';
$db = $cfg['db'];
$mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
if ($mysqli->connect_errno) {
	http_response_code(500);
	die('Database connection failed');
}
$mysqli->set_charset('utf8mb4');


