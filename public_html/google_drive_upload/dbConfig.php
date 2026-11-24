<?php
// Include configuration file (also checks the user is logged in)
require __DIR__ . '/config.php';

// Use the admin panel's database connection (the old separate credentials
// pointed at a database on the previous hosting account and no longer work).
require __DIR__ . '/../admin/database.php';
$db = $con;
