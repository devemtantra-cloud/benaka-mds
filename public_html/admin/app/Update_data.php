<?php require_once __DIR__ . '/auth.php'; require_login();
include '../database.php';
require_once __DIR__ . '/ui.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
require_client_access($con, $id, customers_page());

$mode = 'edit';
$page_title = 'Edit customer';
$c = mysqli_fetch_assoc(db_query($con, "SELECT * FROM `client` WHERE `id` = ?", 'i', [$id]));
$return = back_to(customers_page(), LIST_PAGES);

include "header.php";
include __DIR__ . '/customer_form.php';
