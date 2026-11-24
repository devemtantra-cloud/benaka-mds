<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

$name = isset($_POST['sr_type']) ? $_POST['sr_type'] : '';
$msg = isset($_POST['msg']) ? $_POST['msg'] : '';

$stmt = mysqli_prepare($con, "INSERT INTO `msg` (`Name`, `msg`, `srtype`, `type`) VALUES (?, ?, ?, '2')");
mysqli_stmt_bind_param($stmt, 'sss', $name, $msg, $name);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done('Reminder message added', 'msg.php');
