<?php
session_start();

function logout(){
    // Unset all session variables
    $_SESSION = array();
  
    // Destroy the session
    session_destroy();
    $absolutePath = $root . "/onlicare/Project/index.php";
    header("Location: $absolutePath");
}
  
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    logout();
    echo "awooga";
}

?>