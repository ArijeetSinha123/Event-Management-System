<?php
require_once '../config/database.php';


// If admin is already logged in, redirect to dashboard
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    header('Location: admin/dashboard.php');
    exit;
}

// If regular user is logged in, log them out first
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'student') {
    session_destroy();
    session_start();
}

$error = '';

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password!";
    } else {
        try {
            // Check if user exists and is admin
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                // Admin login successful
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_name'] = $admin['name'];
                $_SESSION['user_email'] = $admin['email'];
                $_SESSION['user_role'] = $admin['role'];
                $_SESSION['student_id'] = $admin['student_id'];
                
                // Set admin session flag
                $_SESSION['admin_logged_in'] = true;
                
                header('Location: admin/dashboard.php');
                exit;
            } else {
                $error = "Invalid admin credentials or you don't have admin privileges!";
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
                    <h4 class="mb-0"><i class="fas fa-lock"></i> Admin Login</h4>
                    <small class="opacity-75">Administrator Access Only</small>
                </div>
                <div class="card-body">
                    <!-- Security Warning -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Warning:</strong> This page is restricted to authorized administrators only.
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Admin Email *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" required 
                                       value="<?php echo $_POST['email'] ?? ''; ?>"
                                       placeholder="admin@college.edu">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Admin Password *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="password" class="form-control" required
                                       placeholder="••••••••">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100 btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login as Administrator
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <div class="border-top pt-3">
                            <p class="text-muted mb-2">Are you a student?</p>
                            <a href="login.php" class="btn btn-outline-primary">
                                <i class="fas fa-user-graduate"></i> Student Login
                            </a>
                            <a href="../index.php" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-home"></i> Back to Home
                            </a>
                        </div>
                    </div>
                    
                    <!-- Admin Credentials Info -->
                    <div class="mt-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="fas fa-info-circle text-info"></i> Default Admin Account:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small><strong>Email:</strong></small><br>
                                        <code>admin@college.edu</code>
                                    </div>
                                    <div class="col-md-6">
                                        <small><strong>Password:</strong></small><br>
                                        <code>password</code>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-lightbulb"></i> 
                                    Contact system administrator to change default credentials.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Security Features Info -->
            <div class="mt-3 text-center">
                <small class="text-muted">
                    <i class="fas fa-shield-alt"></i> 
                    All admin activities are logged and monitored for security purposes.
                </small>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>