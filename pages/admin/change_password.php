<?php
require_once '../../config/database.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../admin_login.php');
    exit;
}

$error = '';
$success = '';

if ($_POST) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    if (empty($current) || empty($new) || empty($confirm)) {
        $error = "All fields required!";
    } elseif ($new !== $confirm) {
        $error = "New passwords don't match!";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Verify current password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $admin = $stmt->fetch();
        
        if (password_verify($current, $admin['password'])) {
            // Update password
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hashed, $_SESSION['user_id']]);
            $success = "Password changed successfully!";
        } else {
            $error = "Current password is incorrect!";
        }
    }
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <a href="dashboard.php" class="btn btn-secondary mb-3">← Back</a>
            
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4>Change Admin Password</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>New Password (min 6 chars)</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>