<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Check if admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../admin_login.php');
    exit;
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">🏠 Admin Dashboard</h1>
    
    <!-- Welcome -->
    <div class="alert alert-danger">
        <h5><i class="fas fa-user-shield"></i> Welcome, Admin <?php echo $_SESSION['user_name']; ?>!</h5>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h2><?php echo getEventCount($pdo); ?></h2>
                    <p>Total Events</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h2><?php echo getUserCount($pdo); ?></h2>
                    <p>Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h2><?php echo getRegistrationCount($pdo); ?></h2>
                    <p>Registrations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h2>Admin</h2>
                    <p><?php echo $_SESSION['user_name']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-plus fa-3x text-primary mb-3"></i>
                    <h5>Manage Events</h5>
                    <a href="manage_events.php" class="btn btn-outline-primary w-100">Go to Events</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h5>Manage Users</h5>
                    <a href="manage_users.php" class="btn btn-outline-success w-100">View Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-key fa-3x text-danger mb-3"></i>
                    <h5>Change Password</h5>
                    <a href="change_password.php" class="btn btn-outline-danger w-100">Change</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>