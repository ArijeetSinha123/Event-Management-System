<?php
// Start session
session_start();

// Clear session
session_unset();
session_destroy();

// Redirect
header("Location: ../index.php");
exit;
?>