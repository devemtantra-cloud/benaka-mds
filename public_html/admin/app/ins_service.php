<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

$sname = isset($_POST['name']) ? trim($_POST['name']) : '';
if ($sname !== '') {
	$stmt = mysqli_prepare($con, "INSERT INTO `service` (`service_name`) VALUES (?)");
	mysqli_stmt_bind_param($stmt, 's', $sname);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

done('Service added', 'add_service.php');
