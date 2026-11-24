<?php require_once __DIR__ . '/auth.php'; require_login(); ?>
<?php
include '../database.php';

$jobdata = isset($_GET['job_data']) ? $_GET['job_data'] : '';
$remark = isset($_GET['remark']) ? $_GET['remark'] : '';
$uid = isset($_GET['uid']) ? (int) $_GET['uid'] : 0;
require_client_access($con, $uid, 'index.php');

$stmt = mysqli_prepare($con, "UPDATE `client` SET `Job_data` = ?, `remark` = ? WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'ssi', $jobdata, $remark, $uid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

auth_redirect('index.php?drs_id=' . $uid);
