<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

$name = isset($_POST['sr_type']) ? $_POST['sr_type'] : '';
$jobdata = isset($_POST['job_data']) ? $_POST['job_data'] : '';
$msg = isset($_POST['msg']) ? $_POST['msg'] : '';

$stmt = mysqli_prepare($con, "INSERT INTO `msg` (`Name`, `msg`, `srtype`, `type`) VALUES (?, ?, ?, '1')");
mysqli_stmt_bind_param($stmt, 'sss', $jobdata, $msg, $name);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done('RTO message added', 'rto_msg.php');
