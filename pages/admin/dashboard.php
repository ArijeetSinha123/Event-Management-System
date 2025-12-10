<?php

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Get statistics
$total_events = getEventCount($pdo);
$total_users = getUserCount($pdo);
$total_registrations = getRegistrationCount($pdo);

// Get recent events
$recent_events = $pdo->query("SELECT * FROM events ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Get recent registrations
$recent_registrations = $pdo->query("
    SELECT u.name, u.student_id, e.title, r.registration_date 
    FROM registrations r 
    JOIN users u ON r.user_id = u.id 
    JOIN events e ON r.event_id = e.id 
    ORDER BY r.registration_date DESC 
    LIMIT 5
")->fetchAll();
?>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <!-- Header with Logout Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
        <div>
            <span class="me-3 text-muted">
                <i class="fas fa-user-shield"></i> Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>
            </span>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="display-6"><?php echo $total_events; ?></h2>
                            <p class="mb-0">Total Events</p>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-75"></i>
                    </div>
                    <a href="manage_events.php" class="text-white text-decoration-none small stretched-link">
                        View all events <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="display-6"><?php echo $total_users; ?></h2>
                            <p class="mb-0">Total Users</p>
                        </div>
                        <i class="fas fa-users fa-3x opacity-75"></i>
                    </div>
                    <a href="manage_users.php" class="text-white text-decoration-none small stretched-link">
                        View all users <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="display-6"><?php echo $total_registrations; ?></h2>
                            <p class="mb-0">Total Registrations</p>
                        </div>
                        <i class="fas fa-clipboard-check fa-3x opacity-75"></i>
                    </div>
                    <a href="manage_registrations.php" class="text-white text-decoration-none small stretched-link">
                        View all registrations <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h5>
                    <small class="opacity-75">Click to perform action</small>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="add_event.php" class="btn btn-outline-primary btn-lg w-100 h-100 d-flex flex-column justify-content-center py-4">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span>Add New Event</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="manage_events.php" class="btn btn-outline-success btn-lg w-100 h-100 d-flex flex-column justify-content-center py-4">
                                <i class="fas fa-edit fa-2x mb-2"></i>
                                <span>Manage Events</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="manage_users.php" class="btn btn-outline-info btn-lg w-100 h-100 d-flex flex-column justify-content-center py-4">
                                <i class="fas fa-user-cog fa-2x mb-2"></i>
                                <span>Manage Users</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="../events.php" class="btn btn-outline-warning btn-lg w-100 h-100 d-flex flex-column justify-content-center py-4">
                                <i class="fas fa-eye fa-2x mb-2"></i>
                                <span>View Events</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i> Recent Events</h5>
                    <a href="manage_events.php" class="text-white text-decoration-none small">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_events)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No events created yet.</p>
                            <a href="add_event.php" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-plus me-1"></i> Create First Event
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_events as $event): ?>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            <?php echo formatDate($event['event_date']); ?> at <?php echo formatTime($event['event_time']); ?>
                                        </small>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i> Recent Registrations</h5>
                    <a href="manage_registrations.php" class="text-white text-decoration-none small">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_registrations)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No registrations yet.</p>
                            <a href="../events.php" class="btn btn-success btn-sm mt-2">
                                <i class="fas fa-calendar-check me-1"></i> Browse Events
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_registrations as $registration): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                                <?php echo htmlspecialchars($registration['name']); ?>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card me-1"></i>
                                                ID: <?php echo htmlspecialchars($registration['student_id']); ?>
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-check me-1"></i>
                                                Registered for <?php echo htmlspecialchars($registration['title']); ?>
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                <?php echo formatDate($registration['registration_date']); ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">New</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>