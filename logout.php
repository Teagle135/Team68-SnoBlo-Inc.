<?php
// logout.php — Ends the user session and redirects to the login page.

session_start();

// Destroy all session data
$_SESSION = [];
session_destroy();

// Redirect to login with a confirmation message in the query string
header('Location: login.php?msg=' . urlencode('You have been logged out successfully.'));
exit;
