<?php
require_once '../config/database.php';

// If admin is already logged in, redirect to DASHBOARD
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    header('Location: admin/dashboard.php');  // ✅ FIXED
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password!";
    } else {
        try {
            // Check if user exists and is admin
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_name'] = $admin['name'];
                $_SESSION['user_email'] = $admin['email'];
                $_SESSION['user_role'] = $admin['role'];
                $_SESSION['student_id'] = $admin['student_id'];
                $_SESSION['admin_logged_in'] = true;
                
                // ✅ CRITICAL FIX: Redirect to ADMIN DASHBOARD
                header('Location: admin/dashboard.php');
                exit;
            } else {
                $error = "Invalid admin credentials!";
            }
        } catch(PDOException $e) {
            $error = "Login failed: " . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-danger text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-user-shield"></i> Admin Login</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Admin Email *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" required 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       placeholder="admin@college.edu">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Admin Password *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100 btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Admin Login
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p class="mb-0">Default: admin@college.edu / password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>