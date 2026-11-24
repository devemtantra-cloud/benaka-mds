<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

// Several pages share this script; ?t= says which list the row belongs to.
$targets = [
	'msg'      => ['msg', 'msg.php'],
	'rules'    => ['rules', 'add_details.php'],
	'feedback' => ['feeback', 'feedback_list.php'],
];
$t = isset($_GET['t']) && isset($targets[$_GET['t']]) ? $_GET['t'] : 'msg';
list($table, $back) = $targets[$t];
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = mysqli_prepare($con, "DELETE FROM `$table` WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done($t === 'rules' ? 'Content deleted' : ($t === 'feedback' ? 'Feedback deleted' : 'Message deleted'), $back);
