<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

$menu = isset($_POST['menu']) ? $_POST['menu'] : '';
$heading = isset($_POST['Headng']) ? $_POST['Headng'] : '';
$points = isset($_POST['points']) ? trim($_POST['points']) : '';

$stmt = mysqli_prepare($con, "INSERT INTO `rules` (`page`, `heading`, `points`) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sss', $menu, $heading, $points);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done($ok ? 'Service page content added' : 'Could not save the content', 'add_details.php', $ok ? 'success' : 'error');
