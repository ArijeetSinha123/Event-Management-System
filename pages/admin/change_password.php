<?php
require_once '../../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../admin_login.php');
    exit;
}

if ($_POST) {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];
    
    if ($new === $confirm) {
        // Verify current password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $admin = $stmt->fetch();
        
        if (password_verify($current, $admin['password'])) {
            // Update password
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hashed, $_SESSION['user_id']]);
            
            echo "<div class='alert alert-success'>Password changed! <a href='dashboard.php'>Go to Dashboard</a></div>";
        } else {
            echo "<div class='alert alert-danger'>Current password is wrong!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>New passwords don't match!</div>";
    }
}
?>

<h3>Change Admin Password</h3>
<form method="POST">
    <input type="password" name="current" placeholder="Current Password" required><br><br>
    <input type="password" name="new" placeholder="New Password" required><br><br>
    <input type="password" name="confirm" placeholder="Confirm New Password" required><br><br>
    <button type="submit">Change Password</button>
</form>