<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get event ID from query string
$event_id = $_GET['event_id'] ?? null;

// Get all events for dropdown
$events = $pdo->query("SELECT * FROM events ORDER BY event_date")->fetchAll();

if ($event_id) {
    // Get participants for specific event
    $stmt = $pdo->prepare("
        SELECT u.student_id, u.name, u.email, r.registration_date 
        FROM registrations r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.event_id = ? 
        ORDER BY r.registration_date
    ");
    $stmt->execute([$event_id]);
    $participants = $stmt->fetchAll();
    
    // Get event details
    $event_stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $event_stmt->execute([$event_id]);
    $current_event = $event_stmt->fetch();
} else {
    // Get all participants across all events
    $stmt = $pdo->query("
        SELECT e.title as event_title, u.student_id, u.name, u.email, r.registration_date 
        FROM registrations r 
        JOIN users u ON r.user_id = u.id 
        JOIN events e ON r.event_id = e.id 
        ORDER BY e.event_date, r.registration_date
    ");
    $all_participants = $stmt->fetchAll();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">👥 Event Participants</h1>

    <!-- Event Selection -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Participants</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Select Event:</label>
                    <select name="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Events</option>
                        <?php foreach ($events as $event): ?>
                            <option value="<?php echo $event['id']; ?>" 
                                <?php echo ($event_id == $event['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($event['title'] . ' - ' . formatDate($event['event_date'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Show Participants</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Participants List -->
    <?php if ($event_id && $current_event): ?>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-users"></i> 
                    Participants for: <?php echo htmlspecialchars($current_event['title']); ?>
                    <span class="badge bg-light text-dark ms-2"><?php echo count($participants); ?> registered</span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($participants)): ?>
                    <div class="alert alert-warning text-center">
                        <h5>No participants registered for this event yet.</h5>
                        <p>Be the first to <a href="events.php" class="alert-link">register</a>!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($participants as $participant): ?>
                                    <tr>
                                        <td><?php echo $participant['student_id']; ?></td>
                                        <td><?php echo htmlspecialchars($participant['name']); ?></td>
                                        <td><?php echo htmlspecialchars($participant['email']); ?></td>
                                        <td><?php echo formatDate($participant['registration_date']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif (!$event_id): ?>
        <!-- All Participants View -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> All Participants Across Events</h5>
            </div>
            <div class="card-body">
                <?php if (empty($all_participants)): ?>
                    <div class="alert alert-info text-center">
                        <h5>No registrations found.</h5>
                        <p><a href="events.php" class="alert-link">Register for events</a> to see participants here.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_participants as $participant): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($participant['event_title']); ?></strong></td>
                                        <td><?php echo $participant['student_id']; ?></td>
                                        <td><?php echo htmlspecialchars($participant['name']); ?></td>
                                        <td><?php echo htmlspecialchars($participant['email']); ?></td>
                                        <td><?php echo formatDate($participant['registration_date']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>s