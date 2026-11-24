<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$pname = isset($_POST['pname']) ? $_POST['pname'] : '';
$pheading = isset($_POST['pheading']) ? $_POST['pheading'] : '';
$points = isset($_POST['point']) ? $_POST['point'] : '';

$stmt = mysqli_prepare($con, "UPDATE `rules` SET `page` = ?, `heading` = ?, `points` = ? WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'sssi', $pname, $pheading, $points, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done('Service page content updated', 'add_details.php');
