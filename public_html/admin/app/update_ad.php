<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

function post($key)
{
	return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$id = post('id');
$name = post('name');
$add = post('add2');
$bname = post('bname');
$pb = post('pb');
$wh = post('wh');

$stmt = mysqli_prepare($con, "UPDATE `branch` SET `name` = ?, `address` = ?, `branch_name` = ?, `phone` = ?, `whatsapp_no` = ? WHERE `branch_id` = ?");
mysqli_stmt_bind_param($stmt, 'ssssss', $name, $add, $bname, $pb, $wh, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done('Branch address updated', 'editadd.php');
