<?php
require_once '../config/database.php';

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header('Location: events.php');
    exit;
}

$error = '';
$success = '';

if ($_POST) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    // Validation
    if (empty($student_id) || empty($name) || empty($email) || empty($password)) {
        $error = "All fields required!";
    } elseif ($password !== $confirm) {
        $error = "Passwords don't match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        try {
            // Check if student ID exists
            $check = $pdo->prepare("SELECT id FROM users WHERE student_id = ?");
            $check->execute([$student_id]);
            if ($check->rowCount() > 0) {
                $error = "Student ID already registered!";
            } else {
                // Check if email exists
                $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $check->execute([$email]);
                if ($check->rowCount() > 0) {
                    $error = "Email already registered!";
                } else {
                    // Create user
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (student_id, name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$student_id, $name, $email, $phone, $hashed]);
                    $success = "Registration successful! Please login.";
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
                <div class="card-header bg-success text-white">
                    <h4>Student Registration</h4>
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
                            <label>Student ID *</label>
                            <input type="number" name="student_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Password (min 6 chars) *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password *</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Already have account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>