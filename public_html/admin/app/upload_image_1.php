<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';
require_once __DIR__ . '/image_upload.php';

$path = save_uploaded_image('choosefile', 'service', $error);
if ($path) {
    $stmt = mysqli_prepare($con, "INSERT INTO `service_pht` (`file_name`) VALUES (?)");
    mysqli_stmt_bind_param($stmt, 's', $path);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

done($path ? 'Photo uploaded' : $error, 'add_service_photo.php', $path ? 'success' : 'error');
