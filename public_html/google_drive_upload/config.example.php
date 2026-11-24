<?php
// Copy this file to config.php (same folder) and fill in the Google OAuth values.
// config.php is git-ignored: never commit the real client secret.
// Only logged-in admin/branch users may use the Drive upload.
require_once __DIR__ . '/../admin/app/auth.php';
if (!is_logged_in()) {
    auth_redirect('/admin/app/login.php');
}
// Database: uses the same connection as the admin panel (see dbConfig.php).

// Google API configuration 
define('GOOGLE_CLIENT_ID', 'YOUR_CLIENT_ID.apps.googleusercontent.com'); 
define('GOOGLE_CLIENT_SECRET', 'YOUR_CLIENT_SECRET'); 
define('GOOGLE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/drive'); 
define('REDIRECT_URI', 'https://benakamds.in/google_drive_upload/google_drive_sync.php'); 
 
// Start session 
if(!session_id()) session_start(); 
 
// Google OAuth URL 
$googleOauthURL = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode(GOOGLE_OAUTH_SCOPE) . '&redirect_uri=' . REDIRECT_URI . '&response_type=code&client_id=' . GOOGLE_CLIENT_ID . '&access_type=online'; 
 
?>