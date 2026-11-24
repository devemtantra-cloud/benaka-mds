<?php require_once __DIR__ . '/auth.php'; require_login();
// Branch dashboard (the main admin is sent to admin_index.php by the menu).
require __DIR__ . '/dashboard_view.php';
