<?php
include('../database.php');
require_once __DIR__ . '/auth.php';

function login_failed()
{
    // Back to the sign-in page with a message; keep the username typed.
    $u = isset($_POST['uname']) ? trim($_POST['uname']) : '';
    auth_redirect('login.php?e=1' . ($u !== '' ? '&u=' . rawurlencode($u) : ''));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['submit'])) {
    auth_redirect('login.php');
}

$uname = trim(isset($_POST['uname']) ? $_POST['uname'] : '');
$psw = isset($_POST['psw']) ? $_POST['psw'] : '';
if ($uname === '' || $psw === '') {
    login_failed();
}

$stmt = mysqli_prepare($con, "SELECT `id`, `uname`, `password`, `utype`, `branch` FROM `login` WHERE `uname` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 's', $uname);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$row) {
    login_failed();
}

$stored = $row['password'];
$is_hashed = !empty(password_get_info($stored)['algo']);

if ($is_hashed) {
    $ok = password_verify($psw, $stored);
} else {
    // Legacy plain-text password: accept once, then upgrade it to a hash.
    $ok = hash_equals($stored, $psw);
}

if (!$ok) {
    login_failed();
}

// Only upgrade once migration.sql has widened the column, or the hash would be truncated.
if ((!$is_hashed || password_needs_rehash($stored, PASSWORD_DEFAULT)) && password_column_fits_hash($con)) {
    $hash = password_hash($psw, PASSWORD_DEFAULT);
    $up = mysqli_prepare($con, "UPDATE `login` SET `password` = ? WHERE `id` = ?");
    mysqli_stmt_bind_param($up, 'si', $hash, $row['id']);
    mysqli_stmt_execute($up);
    mysqli_stmt_close($up);
}

session_regenerate_id(true);
$_SESSION["uname"] = $row['uname'];
$_SESSION["user"] = $row['utype'];
$_SESSION["branch"] = $row['branch'];

auth_redirect($row['utype'] === ADMIN_UTYPE ? 'admin_index.php' : 'index.php');
