<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';
require_once __DIR__ . '/image_upload.php';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = mysqli_prepare($con, "SELECT `file_name` FROM `service_pht` WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_row(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if ($row) {
    $stmt = mysqli_prepare($con, "DELETE FROM `service_pht` WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    delete_image_file($row[0], 'service');
}

done('Photo deleted', 'add_service_photo.php');
