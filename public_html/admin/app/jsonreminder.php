<?php require_once __DIR__ . '/auth.php'; require_login(); ?>
<?php
// "Upload Drive" button: writes the reminder list (clients whose document expires
// within 15 days, or already expired) to json_rem.txt, then hands over to the
// Google Drive uploader. Same JSON keys as before, now built with json_encode.
include '../database.php';

$Date = date("Y-m-d");
$branch_id = user_branch();

if ($branch_id !== '') {
    $stmt = mysqli_prepare($con, "SELECT `branch_name` FROM `branch` WHERE `branch_id` = ?");
    mysqli_stmt_bind_param($stmt, 's', $branch_id);
    mysqli_stmt_execute($stmt);
    $row1 = mysqli_fetch_row(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    $branch_name = $row1 ? $row1[0] : '';

    $stmt = mysqli_prepare($con, "SELECT `name`, `w_number`, `sr_type`, `doc_number`, `v_no`, `exp_date` FROM `client` WHERE `branch_id` = ?");
    mysqli_stmt_bind_param($stmt, 's', $branch_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $branch_name = "Super_Admin";
    $result = mysqli_query($con, "SELECT `name`, `w_number`, `sr_type`, `doc_number`, `v_no`, `exp_date` FROM `client`");
}

$rowcount = mysqli_num_rows($result);
$names = [];
while ($row = mysqli_fetch_assoc($result)) {
    $altdate = date('Y-m-d', strtotime($row["exp_date"] . ' - 15 days'));
    if ($Date >= $altdate) {
        $names[] = [
            "name" => $row['name'],
            "num" => $row['w_number'],
            "document type" => $row['sr_type'],
            "document number" => $row['doc_number'],
            "vehicle number" => $row['v_no'],
            "Expiry date" => $row['exp_date'],
        ];
    }
}

$json = json_encode([
    "branch" => $branch_name,
    "total entry" => (string) $rowcount,
    "names" => $names,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

file_put_contents(__DIR__ . "/json_rem.txt", $json);

auth_redirect('/google_drive_upload/upload.php');
