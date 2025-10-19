<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Get all users with their registration count
$users = $pdo->query("
    SELECT u.*, COUNT(r.id) as registration_count 
    FROM users u 
    LEFT JOIN registrations r ON u.id = r.user_id 
    GROUP BY u.id 
    ORDER BY u.created_at DESC
")->fetchAll();
?>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">👥 Manage Users</h1>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-users"></i> All Users (<?php echo count($users); ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($users)): ?>
                <div class="alert alert-info text-center">
                    <h5>No users found.</h5>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registrations</th>
                                <th>Joined Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['student_id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'success'; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo $user['registration_count']; ?> events</span>
                                    </td>
                                    <td><?php echo formatDate($user['created_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?php echo count($users); ?></h3>
                    <p class="mb-0">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?php echo count(array_filter($users, fn($user) => $user['role'] == 'student')); ?></h3>
                    <p class="mb-0">Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?php echo count(array_filter($users, fn($user) => $user['role'] == 'admin')); ?></h3>
                    <p class="mb-0">Admins</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?php echo array_sum(array_column($users, 'registration_count')); ?></h3>
                    <p class="mb-0">Total Registrations</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>