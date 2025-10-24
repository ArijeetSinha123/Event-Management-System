<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}
require_once '../../config/database.php';
require_once '../../includes/functions.php';
?>
<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Handle form submissions
if ($_POST) {
    if (isset($_POST['add_event'])) {
        // Add new event
        $title = $_POST['title'];
        $description = $_POST['description'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];
        $venue = $_POST['venue'];
        $max_participants = $_POST['max_participants'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, event_time, venue, max_participants) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $event_date, $event_time, $venue, $max_participants]);
            $success = "Event added successfully!";
        } catch(PDOException $e) {
            $error = "Error adding event: " . $e->getMessage();
        }
    } elseif (isset($_POST['delete_event'])) {
        // Delete event
        $event_id = $_POST['event_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
            $stmt->execute([$event_id]);
            $success = "Event deleted successfully!";
        } catch(PDOException $e) {
            $error = "Error deleting event: " . $e->getMessage();
        }
    }
}

// Get all events
$events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
?>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">📋 Manage Events</h1>

    <!-- Success/Error Messages -->
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Add Event Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus"></i> Add New Event</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Event Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Time</label>
                            <input type="time" name="event_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Venue</label>
                            <input type="text" name="venue" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Participants</label>
                            <input type="number" name="max_participants" class="form-control" value="100" required>
                        </div>
                        <button type="submit" name="add_event" class="btn btn-success w-100">
                            <i class="fas fa-save"></i> Add Event
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Events List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> All Events (<?php echo count($events); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($events)): ?>
                        <div class="alert alert-info text-center">
                            <h5>No events found.</h5>
                            <p>Add your first event using the form on the left.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Venue</th>
                                        <th>Max Participants</th>
                                        <th>Actions</th>
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
                                            <td><?php echo $event['max_participants']; ?></td>
                                            <td>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                    <button type="submit" name="delete_event" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Are you sure you want to delete this event?')">
                                                        <i class="fas fa-trash"></i> Delete
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