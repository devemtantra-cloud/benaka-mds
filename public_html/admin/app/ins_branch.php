<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

function post($key)
{
	return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$sname = post('name');
$address = post('Branch_Address');
$branch_name = post('Branch_name');
$phone = post('Phone');
$whatsapp = post('Whatsapp');

$stmt = mysqli_prepare($con, "INSERT INTO `branch` (`name`, `address`, `branch_name`, `phone`, `whatsapp_no`) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sssss', $sname, $address, $branch_name, $phone, $whatsapp);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done('Branch added', 'add_branch.php');
