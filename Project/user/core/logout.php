<?php
session_start();

function logout(){
    // Unset all session variables
    $_SESSION = array();
  
    // Destroy the session
    session_destroy();
}
  
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    logout();
}

?>