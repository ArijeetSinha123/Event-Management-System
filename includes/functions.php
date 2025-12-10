<?php
// Event Management System - Utility Functions
// File: includes/functions.php

/**
 * Get total number of events
 */
function getEventCount($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM events");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch(Exception $e) {
        return 0;
    }
}

/**
 * Get total number of users
 */
function getUserCount($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch(Exception $e) {
        return 0;
    }
}

/**
 * Get total number of registrations
 */
function getRegistrationCount($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM registrations");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch(Exception $e) {
        return 0;
    }
}

/**
 * Format date for display (Jan 15, 2024)
 */
function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

/**
 * Format time for display (09:30 AM)
 */
function formatTime($time) {
    return date('h:i A', strtotime($time));
}

/**
 * Check if user is registered for an event
 */
function isUserRegistered($pdo, $user_id, $event_id) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM registrations WHERE user_id = ? AND event_id = ?");
        $stmt->execute([$user_id, $event_id]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    } catch(Exception $e) {
        return false;
    }
}

/**
 * Get registration count for specific event - CRITICAL FOR events.php
 */
function getRegistrationCountForEvent($pdo, $event_id) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM registrations WHERE event_id = ?");
        $stmt->execute([$event_id]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch(Exception $e) {
        return 0;
    }
}
?>