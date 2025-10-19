<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get all events with registration count
$stmt = $pdo->query("
    SELECT e.*, COUNT(r.id) as registered_count 
    FROM events e 
    LEFT JOIN registrations r ON e.id = r.event_id 
    GROUP BY e.id 
    ORDER BY e.event_date, e.event_time
");
$events = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">⏰ Event Schedule</h1>

    <?php if (empty($events)): ?>
        <div class="alert alert-info text-center">
            <h4>No events scheduled at the moment.</h4>
            <p>Check back later for upcoming events.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Event Title</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Registrations</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($event['title']); ?></strong>
                                <br><small class="text-muted"><?php echo htmlspecialchars($event['description']); ?></small>
                            </td>
                            <td><?php echo formatDate($event['event_date']); ?></td>
                            <td><?php echo formatTime($event['event_time']); ?></td>
                            <td><?php echo htmlspecialchars($event['venue']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($event['registered_count'] < $event['max_participants']) ? 'success' : 'danger'; ?>">
                                    <?php echo $event['registered_count'] . ' / ' . $event['max_participants']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="events.php#event-<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-info-circle"></i> Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Calendar View -->
        <div class="card mt-5">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-calendar-alt"></i> Calendar View</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php 
                    // Group events by month
                    $eventsByMonth = [];
                    foreach ($events as $event) {
                        $month = date('F Y', strtotime($event['event_date']));
                        $eventsByMonth[$month][] = $event;
                    }
                    
                    foreach ($eventsByMonth as $month => $monthEvents): 
                    ?>
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary"><?php echo $month; ?></h5>
                            <div class="list-group">
                                <?php foreach ($monthEvents as $event): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                            <small><?php echo formatDate($event['event_date']); ?></small>
                                        </div>
                                        <p class="mb-1 small"><?php echo formatTime($event['event_time']); ?> at <?php echo htmlspecialchars($event['venue']); ?></p>
                                        <small class="text-muted"><?php echo $event['registered_count']; ?> registrations</small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>