<?php require_once __DIR__ . '/auth.php'; require_login(); ?>
<?php
include '../database.php';

$jobdata = isset($_GET['job_data']) ? $_GET['job_data'] : '';
$remark = isset($_GET['remark']) ? $_GET['remark'] : '';
$uid = isset($_GET['uid']) ? (int) $_GET['uid'] : 0;
require_client_access($con, $uid, 'rto.php');

$stmt = mysqli_prepare($con, "UPDATE `client` SET `Job_data` = ?, `remark` = ? WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'ssi', $jobdata, $remark, $uid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Keep a history entry of every job/remark change.
$stmt = mysqli_prepare($con, "INSERT INTO `history` (`name`, `v_no`, `w_number`, `sr_type`, `doc_number`, `exp_date`, `branch_id`, `wt_status`, `v_name`, `Job_data`, `remark`, `case_worker`)
	SELECT `name`, `v_no`, `w_number`, `sr_type`, `doc_number`, `exp_date`, `branch_id`, `wt_status`, `v_name`, ?, ?, `case_worker` FROM `client` WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'ssi', $jobdata, $remark, $uid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

auth_redirect('rto.php?drs_id=' . $uid);
