<?php
include '../config/database.php';
include '../includes/header.php';

if ($_POST) {
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    
    // Check if already registered
    $checkStmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $checkStmt->execute([$user_id, $event_id]);
    
    if ($checkStmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $event_id]);
        echo "<div class='alert alert-success'>Registration successful!</div>";
    } else {
        echo "<div class='alert alert-warning'>You are already registered for this event.</div>";
    }
}

$event_id = $_GET['event_id'] ?? '';
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<div class="container">
    <h2>Register for Event</h2>
    <form method="POST">
        <div class="form-group">
            <label>Select Student:</label>
            <select name="user_id" class="form-control" required>
                <option value="">Select Student</option>
                <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>">
                    <?= htmlspecialchars($user['student_id']) ?> - <?= htmlspecialchars($user['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="hidden" name="event_id" value="<?= $event_id ?>">
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>