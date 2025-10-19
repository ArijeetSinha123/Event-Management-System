<?php
include '../config/database.php';
include '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date, event_time");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>All Events</h2>
    <div class="row">
        <?php foreach ($events as $event): ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
                    <p><strong>Date:</strong> <?= $event['event_date'] ?></p>
                    <p><strong>Time:</strong> <?= $event['event_time'] ?></p>
                    <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?></p>
                    <a href="register.php?event_id=<?= $event['id'] ?>" class="btn btn-success">Register</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>