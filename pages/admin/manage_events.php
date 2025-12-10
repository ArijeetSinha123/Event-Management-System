<?php
require_once '../../config/database.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../admin_login.php');
    exit;
}

$error = '';
$success = '';

// Add event
if ($_POST && isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $venue = $_POST['venue'];
    $max = $_POST['max_participants'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, event_time, venue, max_participants) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $event_date, $event_time, $venue, $max]);
        $success = "Event added successfully!";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Delete event
if ($_POST && isset($_POST['delete_event'])) {
    $event_id = $_POST['event_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$event_id]);
        $success = "Event deleted!";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Get all events
$events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
?>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">Manage Events</h1>
    
    <!-- Messages -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Add Event Form -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Add New Event</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Event Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Time</label>
                            <input type="time" name="event_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Venue</label>
                            <input type="text" name="venue" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Max Participants</label>
                            <input type="number" name="max_participants" class="form-control" value="100">
                        </div>
                        <button type="submit" name="add_event" class="btn btn-success w-100">Add Event</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Events List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>All Events (<?php echo count($events); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($events)): ?>
                        <p class="text-center">No events found.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Venue</th>
                                        <th>Max</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($events as $event): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                                            <td><?php echo $event['event_date']; ?></td>
                                            <td><?php echo $event['event_time']; ?></td>
                                            <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                            <td><?php echo $event['max_participants']; ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                    <button type="submit" name="delete_event" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Delete this event?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>