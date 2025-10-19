<?php
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
    <h1 class="text-center mb-4">🏠 Admin Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><?php echo $total_events; ?></h4>
                            <p class="mb-0">Total Events</p>
                        </div>
                        <i class="fas fa-calendar fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><?php echo $total_users; ?></h4>
                            <p class="mb-0">Total Users</p>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><?php echo $total_registrations; ?></h4>
                            <p class="mb-0">Total Registrations</p>
                        </div>
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="manage_events.php" class="btn btn-outline-primary btn-lg w-100">
                                <i class="fas fa-plus"></i><br>Add Event
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="manage_events.php" class="btn btn-outline-success btn-lg w-100">
                                <i class="fas fa-edit"></i><br>Manage Events
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="manage_users.php" class="btn btn-outline-info btn-lg w-100">
                                <i class="fas fa-user-cog"></i><br>Manage Users
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="../events.php" class="btn btn-outline-warning btn-lg w-100">
                                <i class="fas fa-eye"></i><br>View Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Events</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_events)): ?>
                        <p class="text-muted">No events created yet.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($recent_events as $event): ?>
                                <div class="list-group-item">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                    <small class="text-muted">
                                        <?php echo formatDate($event['event_date']); ?> at <?php echo formatTime($event['event_time']); ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Recent Registrations</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_registrations)): ?>
                        <p class="text-muted">No registrations yet.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($recent_registrations as $registration): ?>
                                <div class="list-group-item">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($registration['name']); ?></h6>
                                    <small class="text-muted">
                                        Registered for <?php echo htmlspecialchars($registration['title']); ?>
                                        on <?php echo formatDate($registration['registration_date']); ?>
                                    </small>
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