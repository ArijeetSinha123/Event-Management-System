<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Handle registration
if ($_POST && isset($_POST['user_id']) && isset($_POST['event_id'])) {
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    
    try {
        // Check if already registered
        if (!isUserRegistered($pdo, $user_id, $event_id)) {
            $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $event_id]);
            $success = "Registration successful!";
        } else {
            $error = "You are already registered for this event.";
        }
    } catch(PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}

// Get all events
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date, event_time");
$events = $stmt->fetchAll();

// Get all users for registration
$users = $pdo->query("SELECT * FROM users WHERE role = 'student'")->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">📅 All Events</h1>

    <!-- Success/Error Messages -->
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <?php if (empty($events)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No events available at the moment.</h4>
                    <p>Check back later for upcoming events.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-6 mb-4">
                    <div class="card event-card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($event['title']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                            <div class="event-details">
                                <p><strong><i class="fas fa-calendar"></i> Date:</strong> <?php echo formatDate($event['event_date']); ?></p>
                                <p><strong><i class="fas fa-clock"></i> Time:</strong> <?php echo formatTime($event['event_time']); ?></p>
                                <p><strong><i class="fas fa-map-marker-alt"></i> Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                                <p><strong><i class="fas fa-users"></i> Max Participants:</strong> <?php echo $event['max_participants']; ?></p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- Registration Form -->
                            <form method="POST" class="row g-2 align-items-center">
                                <div class="col-md-7">
                                    <select name="user_id" class="form-select" required>
                                        <option value="">Select Student</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo $user['id']; ?>">
                                                <?php echo htmlspecialchars($user['student_id'] . ' - ' . $user['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-user-plus"></i> Register
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>