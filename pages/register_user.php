<?php
require_once '../config/database.php';

$error = '';
$success = '';

if ($_POST) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($student_id) || empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        try {
            // Check if student ID already exists
            $check_stmt = $pdo->prepare("SELECT id FROM users WHERE student_id = ?");
            $check_stmt->execute([$student_id]);
            
            if ($check_stmt->rowCount() > 0) {
                $error = "Student ID already registered!";
            } else {
                // Check if email already exists
                $check_stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $check_stmt->execute([$email]);
                
                if ($check_stmt->rowCount() > 0) {
                    $error = "Email already registered!";
                } else {
                    // Insert new user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (student_id, name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$student_id, $name, $email, $phone, $hashed_password]);
                    
                    $success = "Registration successful! You can now login.";
                }
            }
        } catch(PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus"></i> User Registration</h4>
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
                            <label class="form-label">Student ID *</label>
                            <input type="number" name="student_id" class="form-control" required 
                                   value="<?php echo $_POST['student_id'] ?? ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required 
                                   value="<?php echo $_POST['name'] ?? ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required 
                                   value="<?php echo $_POST['email'] ?? ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" 
                                   value="<?php echo $_POST['phone'] ?? ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" required 
                                   minlength="6">
                            <small class="text-muted">Password must be at least 6 characters long</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-user-plus"></i> Register
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>