<?php
// logout.php - UPDATED VERSION
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to homepage with success message
header('Location: ../index.php?logout=success');
exit;
?>