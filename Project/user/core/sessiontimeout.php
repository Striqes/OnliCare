<?php
session_start();

// Set session timeout duration
$timeout_duration = 1800;  // 1800 seconds = 30 minutes

// Check if the timeout field exists
if(isset($_SESSION['timeout'])) {
    // Calculate the session's "age"
    $session_age = time() - $_SESSION['timeout'];

    // Check if the session has expired
    if($session_age > $timeout_duration) {
        // Destroy the session and redirect to login
        session_destroy();
        header('Location: ../login.php');
        exit;
    }
}

// Update the timeout field with the current time
$_SESSION['timeout'] = time();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page
  header('Location: ../login.php');
  exit;
}

echo "User ID: " . $_SESSION['user_id'];

?>
