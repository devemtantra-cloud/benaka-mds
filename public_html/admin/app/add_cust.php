<?php require_once __DIR__ . '/auth.php'; require_login();
include '../database.php';
require_once __DIR__ . '/ui.php';

$mode = 'add';
$page_title = 'Add customer';
$c = ['name' => '', 'v_no' => '', 'w_number' => '', 'wt_status' => '', 'sr_type' => '', 'branch_id' => user_branch(),
      'doc_number' => '', 'exp_date' => '', 'd_inward' => '', 'case_worker' => ''];
$return = back_to(customers_page(), LIST_PAGES);

include "header.php";
include __DIR__ . '/customer_form.php';
