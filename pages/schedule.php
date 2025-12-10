<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get all events
$events = $pdo->query("SELECT * FROM events ORDER BY event_date, event_time")->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">Event Schedule</h1>
    
    <?php if (empty($events)): ?>
        <div class="alert alert-info">
            <p class="text-center">No events scheduled yet.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($event['title']); ?></strong></td>
                            <td><?php echo formatDate($event['event_date']); ?></td>
                            <td><?php echo formatTime($event['event_time']); ?></td>
                            <td><?php echo htmlspecialchars($event['venue']); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>