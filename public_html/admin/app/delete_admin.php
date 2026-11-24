<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Don't let an admin delete the account they are logged in with.
$stmt = mysqli_prepare($con, "DELETE FROM `login` WHERE `id` = ? AND `uname` <> ?");
mysqli_stmt_bind_param($stmt, 'is', $id, $_SESSION['uname']);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

done('User deleted', 'add_admin.php');
