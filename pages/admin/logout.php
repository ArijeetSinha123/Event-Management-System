<?php
// Start session
session_start();

// Clear session
session_unset();
session_destroy();

// Redirect
header("Location: ../admin_login.php");
exit;
?>