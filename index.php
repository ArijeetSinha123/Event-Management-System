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
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt"></i> Event Management System
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Event Management System</h1>
            <p class="lead">Efficiently manage events, registrations, and participants</p>
            <a href="pages/events.php" class="btn btn-light btn-lg mt-3">Get Started</a>
        </div>
    </section>

    <!-- Statistics Section -->
    <div class="container mt-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar feature-icon text-primary"></i>
                        <h3><?php echo getEventCount($pdo); ?></h3>
                        <p class="card-text">Total Events</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-users feature-icon text-success"></i>
                        <h3><?php echo getUserCount($pdo); ?></h3>
                        <p class="card-text">Registered Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-check-circle feature-icon text-warning"></i>
                        <h3><?php echo getRegistrationCount($pdo); ?></h3>
                        <p class="card-text">Total Registrations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">System Features</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-list-alt fa-3x text-primary mb-3"></i>
                        <h5>Event Listing</h5>
                        <p>Browse all available events with details</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-user-plus fa-3x text-success mb-3"></i>
                        <h5>Easy Registration</h5>
                        <p>Simple event registration process</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                        <h5>Event Schedule</h5>
                        <p>View event timings and schedules</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                        <h5>Admin Dashboard</h5>
                        <p>Manage events and participants</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Quick Access</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <a href="pages/events.php" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-list"></i><br>Events
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="pages/schedule.php" class="btn btn-outline-success btn-lg w-100">
                                    <i class="fas fa-clock"></i><br>Schedule
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="pages/participants.php" class="btn btn-outline-info btn-lg w-100">
                                    <i class="fas fa-users"></i><br>Participants
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Authentication Section -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-user-circle"></i> Get Started</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-6 mb-3">
                                <a href="pages/register_user.php" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-user-plus"></i><br>New User Registration
                                </a>
                                <p class="mt-2 text-muted">Create your account to register for events</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="pages/login.php" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-sign-in-alt"></i><br>Existing User Login
                                </a>
                                <p class="mt-2 text-muted">Already have an account? Login here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2024 Event Management System. Developed by Jack Troller.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>