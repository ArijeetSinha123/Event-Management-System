<?php
// Utility functions

function getEventCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM events");
    return $stmt->fetch()['count'];
}

function getUserCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    return $stmt->fetch()['count'];
}

function getRegistrationCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM registrations");
    return $stmt->fetch()['count'];
}

function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

function formatTime($time) {
    return date('h:i A', strtotime($time));
}

function isUserRegistered($pdo, $user_id, $event_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM registrations WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$user_id, $event_id]);
    return $stmt->fetch()['count'] > 0;
}
?>