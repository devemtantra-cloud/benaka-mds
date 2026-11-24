<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

function back_with($msg, $type = 'error')
{
	done($msg, 'add_admin.php', $type);
}

$uname = trim(isset($_POST['uname']) ? $_POST['uname'] : '');
$psw = isset($_POST['password']) ? $_POST['password'] : '';
$utype = isset($_POST['utype']) ? $_POST['utype'] : '';
$branch = isset($_POST['branch']) ? $_POST['branch'] : '';

if ($uname === '' || $psw === '') {
	back_with('Username and password are required');
}
if (!password_column_fits_hash($con)) {
	back_with('Run migration.sql before adding users');
}

$chk = mysqli_prepare($con, "SELECT 1 FROM `login` WHERE `uname` = ? LIMIT 1");
mysqli_stmt_bind_param($chk, 's', $uname);
mysqli_stmt_execute($chk);
mysqli_stmt_store_result($chk);
$exists = mysqli_stmt_num_rows($chk) > 0;
mysqli_stmt_close($chk);
if ($exists) {
	back_with('That username already exists');
}

$hash = password_hash($psw, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($con, "INSERT INTO `login` (`uname`,`password`,`utype`,`branch`) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'ssss', $uname, $hash, $utype, $branch);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

back_with($ok ? 'User “' . $uname . '” added' : 'Could not add user', $ok ? 'success' : 'error');
