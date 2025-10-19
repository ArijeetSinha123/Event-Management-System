<?php include 'includes/header.php'; ?>
<div class="container">
    <h1>Welcome to Event Management System</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Events</h5>
                    <p class="card-text">Browse all available events</p>
                    <a href="pages/events.php" class="btn btn-primary">View Events</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Schedule</h5>
                    <p class="card-text">Check event schedules</p>
                    <a href="pages/schedule.php" class="btn btn-primary">View Schedule</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Participants</h5>
                    <p class="card-text">See who's attending</p>
                    <a href="pages/participants.php" class="btn btn-primary">View Participants</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>