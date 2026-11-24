<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';
require_once __DIR__ . '/image_upload.php';

$path = save_uploaded_image('choosefile', 'slider', $error);
if ($path) {
    $stmt = mysqli_prepare($con, "INSERT INTO `slider` (`filename`) VALUES (?)");
    mysqli_stmt_bind_param($stmt, 's', $path);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

done($path ? 'Slide uploaded — it is now on the home page' : $error, 'add_slider.php', $path ? 'success' : 'error');
