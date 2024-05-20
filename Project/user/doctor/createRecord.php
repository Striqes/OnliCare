<?php

include '../core/connection.php';

// Get the doctor's ID based on the logged-in user ID
$getDoctorsql = "SELECT Doctor_ID FROM doctor WHERE User_ID = ?";
$getDoctorsql_stmt = $conn->prepare($getDoctorsql);
$getDoctorsql_stmt->bind_param("i", $_SESSION['user_id']);
$getDoctorsql_stmt->execute();
$getDoctor_result = $getDoctorsql_stmt->get_result();
$doctorRow = $getDoctor_result->fetch_assoc();

if (!$doctorRow) {
    exit('Doctor ID not found. Contact Administrator!');
} else {
    $doctor_id = $doctorRow['Doctor_ID'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($doctor_id)) {

    $patient_id = $_POST['patient_id'] ?? null;
    $diagnosis = $_POST['diagnosis'];
    $feedback = $_POST['feedback'];
    $fname = $_POST["first_name"] ?? null;
    $mi = $_POST["middle_initial"] ?? null;
    $lname = $_POST["last_name"] ?? null;

    if ($patient_id) {

        $insert_sql = "INSERT INTO `records` (`RecordID`, `Patient_ID`, `Doctor_ID`, `Diagnosis`, `Feedback`) VALUES (NULL, ?, ?, ?, ?);";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iiss", $patient_id, $doctor_id, $diagnosis, $feedback);

    } elseif ($fname && $lname) {

        $insert_sql = "INSERT INTO `records` (`RecordID`, `fname`, `middle_initial`, `lname`, `Doctor_ID`, `Diagnosis`, `Feedback`) VALUES (NULL, ?, ?, ?, ?, ?, ?);";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssiss", $fname, $mi, $lname, $doctor_id, $diagnosis, $feedback);
        
    } else {
        exit("Error: Either patient ID or patient's full name must be provided.");
    }

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>