<?php
// Shared auth guard for admin/app.
// Usage at the very top of a page:
//   require_once __DIR__ . '/auth.php'; require_login();   // any logged-in user
//   require_once __DIR__ . '/auth.php'; require_admin();   // main admin (utype 'MA') only

const ADMIN_UTYPE = 'MA';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function is_logged_in()
{
    return !empty($_SESSION['uname']);
}

function is_admin()
{
    return is_logged_in() && isset($_SESSION['user']) && $_SESSION['user'] === ADMIN_UTYPE;
}

// Branch of the logged-in user; '' means all branches (main admin).
function user_branch()
{
    return isset($_SESSION['branch']) ? (string) $_SESSION['branch'] : '';
}

// Branch users may only open/change clients of their own branch.
function client_access_ok($con, $client_id)
{
    $stmt = mysqli_prepare($con, "SELECT `branch_id` FROM `client` WHERE `id` = ?");
    $id = (int) $client_id;
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_row(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row && (user_branch() === '' || (string) $row[0] === user_branch());
}

function require_client_access($con, $client_id, $back = 'crud.php')
{
    if (!client_access_ok($con, $client_id)) {
        auth_redirect($back);
    }
}

// Saves the Job status / Remark edited on a dashboard row.
function save_client_job($con, $client_id, $jobdata, $remark)
{
    if (!client_access_ok($con, $client_id)) {
        return false;
    }
    $id = (int) $client_id;
    $stmt = mysqli_prepare($con, "UPDATE `client` SET `Job_data` = ?, `remark` = ? WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, 'ssi', $jobdata, $remark, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

// True once migration.sql has widened login.password to hold password_hash() output.
function password_column_fits_hash($con)
{
    $res = mysqli_query($con, "SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'login' AND COLUMN_NAME = 'password'");
    $row = $res ? mysqli_fetch_row($res) : null;
    return $row && (int) $row[0] >= 255;
}

function auth_redirect($location)
{
    // Clear anything already buffered so no page content leaks with the redirect.
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Location: ' . $location);
    exit;
}

function require_login()
{
    if (!is_logged_in()) {
        auth_redirect('login.php');
    }
}

function require_admin()
{
    require_login();
    if (!is_admin()) {
        // Branch users go back to their own dashboard.
        auth_redirect('index.php');
    }
}
