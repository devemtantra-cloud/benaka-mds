<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

// Only plain font-family names (letters, digits, spaces, commas, hyphens).
$style = isset($_POST['style']) ? trim($_POST['style']) : '';
if ($style !== '' && preg_match('/^[A-Za-z0-9 ,\-]{1,60}$/', $style)) {
	$stmt = mysqli_prepare($con, "UPDATE `font` SET `family` = ? WHERE `id` = 1");
	mysqli_stmt_bind_param($stmt, 's', $style);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

done('Website font updated', 'font.php');
