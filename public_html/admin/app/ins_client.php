<?php require_once __DIR__ . '/auth.php'; require_login(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';

function post($key)
{
	return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$name = post('name');
$v_no = strtoupper(post('v_no'));
$w_number = "91" . preg_replace('/\D+/', '', post('w_number'));
$sr_type = post('sr_type');
$doc_number = post('doc_number');
$exp_date = valid_date(post('exp_date')) ? post('exp_date') : '0000-00-00';
$no_whatsapp = post('no_whatsapp') === 'no' ? 'no' : '';
$d_inward = valid_date(post('d_inward')) ? post('d_inward') : '';
$case_wk = post('case_wk');
$return = safe_return(post('return'), customers_page());

// Branch users always add to their own branch; the main admin picks one.
$branch_id = user_branch() !== '' ? user_branch() : (post('branch_id') !== '' ? post('branch_id') : post('branch_name'));

if ($name == "" || post('w_number') == "" || $sr_type == "" || $doc_number == "" || $branch_id === '') {
	done('Please fill in name, WhatsApp number, service, branch and document number', 'add_cust.php', 'error');
}

$stmt = mysqli_prepare($con, "SELECT 1 FROM `client` WHERE `name` = ? AND `v_no` = ? AND `doc_number` = ? AND `branch_id` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'ssss', $name, $v_no, $doc_number, $branch_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$exists = mysqli_stmt_num_rows($stmt) > 0;
mysqli_stmt_close($stmt);

if (!$exists) {
	// v_name, Job_data and remark have no DB default, so set them explicitly.
	$stmt = mysqli_prepare($con, "INSERT INTO `client` (`name`, `v_no`, `w_number`, `sr_type`, `doc_number`, `exp_date`, `branch_id`, `wt_status`, `d_inward`, `case_worker`, `v_name`, `Job_data`, `remark`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', '', '')");
	mysqli_stmt_bind_param($stmt, 'ssssssssss', $name, $v_no, $w_number, $sr_type, $doc_number, $exp_date, $branch_id, $no_whatsapp, $d_inward, $case_wk);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

if ($exists) {
	done('“' . $name . '” is already in your customer list', $return, 'info');
}
done('Customer “' . $name . '” added', $return);
