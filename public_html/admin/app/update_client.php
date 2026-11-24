<?php require_once __DIR__ . '/auth.php'; require_login(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

function post($key)
{
	return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$id = (int) post('id');
require_client_access($con, $id, customers_page());
$cur = mysqli_fetch_assoc(db_query($con, "SELECT `branch_id`, `wt_status`, `case_worker` FROM `client` WHERE `id` = ?", 'i', [$id]));

$name = post('name');
$v_no = strtoupper(post('v_no'));
$w_number = preg_replace('/\D+/', '', post('w_number'));
$sr_type = post('sr_type');
$doc_number = post('doc_number');
$exp_date = valid_date(post('exp_date')) ? post('exp_date') : '0000-00-00';
$d_inward = valid_date(post('d_inward')) ? post('d_inward') : '';
$return = safe_return(post('return'), customers_page());
// "No WhatsApp" ticked -> 'no'; unticked keeps the old value unless it was 'no'.
$wt_status = post('no_whatsapp') === 'no' ? 'no' : ($cur['wt_status'] === 'no' ? '' : $cur['wt_status']);
$case_worker = isset($_POST['case_wk']) ? post('case_wk') : $cur['case_worker'];

if ($name === '' || $w_number === '' || $sr_type === '' || $doc_number === '') {
	done('Please fill in name, WhatsApp number, service and document number', 'Update_data.php?id=' . $id, 'error');
}

if (user_branch() === '') {
	// Main admin can move a customer to another branch.
	$branches = branches_map($con);
	$branch_n = post('branch_id');
	if (!isset($branches[$branch_n])) {
		// Older form posted the branch name.
		$branch_n = (string) db_value($con, "SELECT `branch_id` FROM `branch` WHERE `branch_name` = ? LIMIT 1", 's', [post('branch_name')]);
	}
	if ($branch_n === '') {
		$branch_n = $cur['branch_id'];
	}
} else {
	$branch_n = user_branch();
}

db_query($con, "UPDATE `client` SET `name` = ?, `v_no` = ?, `d_inward` = ?, `w_number` = ?, `branch_id` = ?, `sr_type` = ?, `doc_number` = ?, `exp_date` = ?, `wt_status` = ?, `case_worker` = ? WHERE `id` = ?",
	'ssssssssssi', [$name, $v_no, $d_inward, $w_number, $branch_n, $sr_type, $doc_number, $exp_date, $wt_status, $case_worker, $id]);

done('Customer “' . $name . '” updated', $return);
