<?php
// Background save of Job status + Remark from the Dashboard and RTO Work lists.
// POST id, mode (dashboard|rto), job, remark, csrf  ->  JSON {ok, msg, wa}
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/ui.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function reply($ok, $msg, $extra = [])
{
    echo json_encode(array_merge(['ok' => $ok, 'msg' => $msg], $extra));
    exit;
}

if (!is_logged_in()) {
    http_response_code(401);
    reply(false, 'Session expired — please log in again.');
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_ok(isset($_POST['csrf']) ? $_POST['csrf'] : '')) {
    http_response_code(400);
    reply(false, 'Page expired — please reload and try again.');
}

include '../database.php';

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$mode = isset($_POST['mode']) && $_POST['mode'] === 'rto' ? 'rto' : 'dashboard';
$job = isset($_POST['job']) ? trim($_POST['job']) : '';
$remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';

if (!save_client_job($con, $id, $job, $remark)) {
    reply(false, 'Not saved — this customer is not in your branch.');
}

if ($mode === 'rto') {
    // Keep a history entry of every RTO job/remark change (as u_rto.php did).
    db_query($con, "INSERT INTO `history` (`name`, `v_no`, `w_number`, `sr_type`, `doc_number`, `exp_date`, `branch_id`, `wt_status`, `v_name`, `Job_data`, `remark`, `case_worker`)
        SELECT `name`, `v_no`, `w_number`, `sr_type`, `doc_number`, `exp_date`, `branch_id`, `wt_status`, `v_name`, `Job_data`, `remark`, `case_worker` FROM `client` WHERE `id` = ?", 'i', [$id]);
}

// Return the WhatsApp link for the new status so the row can update in place.
$c = mysqli_fetch_assoc(db_query($con, "SELECT * FROM `client` WHERE `id` = ?", 'i', [$id]));
$branches = branches_map($con);
$b = $c && isset($branches[(string) $c['branch_id']]) ? $branches[(string) $c['branch_id']] : null;
$tpls = message_templates($con);
$wa = $c ? ($mode === 'rto' ? rto_wa($c, $b, $tpls) : dashboard_wa($c, $b, $tpls)) : null;

reply(true, 'Saved', ['wa' => $wa]);
