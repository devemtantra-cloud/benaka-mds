<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$sname = isset($_POST['sname']) ? $_POST['sname'] : '';
$jobdata = isset($_POST['jobdata']) ? $_POST['jobdata'] : '';
$msg = isset($_POST['msg']) ? $_POST['msg'] : '';

$stmt = mysqli_prepare($con, "UPDATE `msg` SET `Name` = ?, `srtype` = ?, `msg` = ? WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'sssi', $jobdata, $sname, $msg, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done('RTO message updated', 'rto_msg.php');
