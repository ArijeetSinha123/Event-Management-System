<?php
require_once '../config/database.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get event ID from URL
$event_id = $_GET['event_id'] ?? 0;

// Get all events for dropdown
$events = $pdo->query("SELECT id, title FROM events ORDER BY event_date")->fetchAll();

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
    $event_stmt = $pdo->prepare("SELECT title FROM events WHERE id = ?");
    $event_stmt->execute([$event_id]);
    $event = $event_stmt->fetch();
} else {
    // Get all participants
    $all_participants = $pdo->query("
        SELECT e.title as event_title, u.student_id, u.name, u.email, r.registration_date 
        FROM registrations r 
        JOIN users u ON r.user_id = u.id 
        JOIN events e ON r.event_id = e.id 
        ORDER BY e.event_date, r.registration_date
    ")->fetchAll();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">Event Participants</h1>
    
    <!-- Event Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-8">
                    <label>Select Event:</label>
                    <select name="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Events</option>
                        <?php foreach ($events as $ev): ?>
                            <option value="<?php echo $ev['id']; ?>" <?php echo ($event_id == $ev['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ev['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Show Participants</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Participants List -->
    <?php if ($event_id && isset($event)): ?>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Participants for: <?php echo htmlspecialchars($event['title']); ?></h5>
            </div>
            <div class="card-body">
                <?php if (empty($participants)): ?>
                    <p class="text-center">No participants registered yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($participants as $p): ?>
                                    <tr>
                                        <td><?php echo $p['student_id']; ?></td>
                                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['email']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($p['registration_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif (!$event_id): ?>
        <!-- All Participants -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5>All Participants</h5>
            </div>
            <div class="card-body">
                <?php if (empty($all_participants)): ?>
                    <p class="text-center">No registrations found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_participants as $p): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($p['event_title']); ?></strong></td>
                                        <td><?php echo $p['student_id']; ?></td>
                                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['email']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($p['registration_date'])); ?></td>
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

<?php include '../includes/footer.php'; ?>