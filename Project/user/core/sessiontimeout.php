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
if (!isset($_SESSION['user_id']) && !endsWith($_SERVER['REQUEST_URI'], 'index.php')) {
  // Redirect to login page
  header('Location: ../login.php');
  exit;
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
  // Display the button only if user is logged in
  echo "User ID: " . $_SESSION['user_id'];
  echo '<form method="post">';
  echo '<button type="submit" name="logout">Destroy Session</button>';
  echo '</form>';
}

function endsWith($haystack, $needle) {
  $length = strlen($needle);
  if ($length == 0) {
      return true;
  }
  return (substr($haystack, -$length) === $needle);
}

if (isset($_POST['logout'])) {
  // Unset all session variables
  $_SESSION = array();

  // Destroy the session
  session_destroy();
  $absolutePath = $root . "/onlicare/Project/index.php";
  header("Location: $absolutePath");
  
}

?>
