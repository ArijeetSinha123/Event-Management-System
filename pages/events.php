<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle registration
if ($_POST && isset($_POST['event_id'])) {
    $user_id = $_SESSION['user_id'];
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

// Get user's registrations
$user_registrations = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT event_id FROM registrations WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_registrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">📅 All Events</h1>

    <!-- Welcome Message -->
    <div class="alert alert-info">
        <h5><i class="fas fa-user"></i> Welcome, <?php echo $_SESSION['user_name']; ?>!</h5>
        <p class="mb-0">You are logged in as: <strong><?php echo $_SESSION['student_id']; ?></strong></p>
    </div>

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
            <?php foreach ($events as $event): 
                $is_registered = in_array($event['id'], $user_registrations);
                $registration_count = getRegistrationCountForEvent($pdo, $event['id']);
                $is_full = $registration_count >= $event['max_participants'];
            ?>
                <div class="col-md-6 mb-4">
                    <div class="card event-card h-100">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($event['title']); ?></h5>
                            <?php if ($is_registered): ?>
                                <span class="badge bg-success">Registered</span>
                            <?php elseif ($is_full): ?>
                                <span class="badge bg-danger">Full</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                            <div class="event-details">
                                <p><strong><i class="fas fa-calendar"></i> Date:</strong> <?php echo formatDate($event['event_date']); ?></p>
                                <p><strong><i class="fas fa-clock"></i> Time:</strong> <?php echo formatTime($event['event_time']); ?></p>
                                <p><strong><i class="fas fa-map-marker-alt"></i> Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                                <p><strong><i class="fas fa-users"></i> Participants:</strong> 
                                    <?php echo $registration_count . ' / ' . $event['max_participants']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?php if ($is_registered): ?>
                                <button class="btn btn-success w-100" disabled>
                                    <i class="fas fa-check"></i> Already Registered
                                </button>
                            <?php elseif ($is_full): ?>
                                <button class="btn btn-danger w-100" disabled>
                                    <i class="fas fa-times"></i> Event Full
                                </button>
                            <?php else: ?>
                                <form method="POST">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-user-plus"></i> Register for Event
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>