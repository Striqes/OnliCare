<?php
    include '../core/sessiontimeout.php';
    include '../core/connection.php';

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        exit('User is not logged in.');
    }

    // User Type verifcation
    if($_SESSION['UserType'] == 'Patient'){
        header("Location: ..\patient\appointment.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
    }
?>