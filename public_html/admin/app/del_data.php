<?php require_once __DIR__ . '/auth.php'; require_login(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
require_client_access($con, $id, customers_page());

$name = (string) db_value($con, "SELECT `name` FROM `client` WHERE `id` = ?", 'i', [$id]);
db_query($con, "DELETE FROM `client` WHERE `id` = ?", 'i', [$id]);

// Back to the list (same search/page) the user deleted from.
done('Customer “' . $name . '” deleted', back_to(customers_page(), LIST_PAGES));
