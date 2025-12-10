<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1 class="display-4 fw-bold">Event Management System</h1>
        <p class="lead">Manage events, registrations, and participants efficiently</p>
        <div class="mt-4">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="pages/events.php" class="btn btn-light btn-lg">Go to Events</a>
            <?php else: ?>
                <a href="pages/login.php" class="btn btn-light btn-lg">Student Login</a>
                <a href="pages/register_user.php" class="btn btn-outline-light btn-lg">Student Register</a>
                <a href="pages/admin_login.php" class="btn btn-danger btn-lg">Admin Login</a>
            <?php endif; ?>
        </div>
    </div>
</div>

    <!-- Statistics -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar fa-3x text-primary mb-3"></i>
                        <h3><?php echo getEventCount($pdo); ?></h3>
                        <p class="card-text">Total Events</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                        <h3><?php echo getUserCount($pdo); ?></h3>
                        <p class="card-text">Registered Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-3x text-warning mb-3"></i>
                        <h3><?php echo getRegistrationCount($pdo); ?></h3>
                        <p class="card-text">Total Registrations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="pages/events.php" class="btn btn-primary w-100 py-3">
                    <i class="fas fa-list fa-2x mb-2"></i><br>
                    Browse Events
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="pages/schedule.php" class="btn btn-success w-100 py-3">
                    <i class="fas fa-clock fa-2x mb-2"></i><br>
                    View Schedule
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="pages/participants.php" class="btn btn-info w-100 py-3">
                    <i class="fas fa-users fa-2x mb-2"></i><br>
                    See Participants
                </a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>