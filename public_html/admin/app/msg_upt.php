<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$name = isset($_POST['name']) ? $_POST['name'] : '';
$msg = isset($_POST['msg']) ? $_POST['msg'] : '';

$stmt = mysqli_prepare($con, "UPDATE `msg` SET `Name` = ?, `msg` = ? WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'ssi', $name, $msg, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done('Message updated', 'msg.php');
