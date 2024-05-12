<?php
    include '../core/sessiontimeout.php';
    include '../core/connection.php';

    $conn = new mysqli("localhost","root","","onlicare");

    if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
    }

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