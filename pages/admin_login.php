<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] != 'admin') {
    session_destroy();
}

require_once '../config/database.php';

// If already logged in as admin, redirect
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
    header('Location: admin/dashboard.php');
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
            // Check if admin exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                // Set session
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_name'] = $admin['name'];
                $_SESSION['user_email'] = $admin['email'];
                $_SESSION['user_role'] = 'admin';
                $_SESSION['student_id'] = $admin['student_id'];
                $_SESSION['admin_logged_in'] = true;
                
                // ALWAYS go to admin dashboard
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
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-user-shield"></i> Admin Login</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Admin Email</label>
                            <input type="email" name="email" class="form-control" required 
                                   placeholder="admin@college.edu">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Admin Password</label>
                            <input type="password" name="password" class="form-control" required 
                                   placeholder="password">
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100">Admin Login</button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>Default: admin@college.edu / password</p>
                        <p>Student? <a href="login.php">Student Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>