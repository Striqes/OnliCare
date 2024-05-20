<?php

include '../core/connection.php';

$record_id = $_POST["record_id"];

$getrecordsql = "SELECT RecordID FROM records WHERE RecordID = ?";
$getrecordsql_stmt = $conn->prepare($getrecordsql);
$getrecordsql_stmt->bind_param("i", $record_id);
$getrecordsql_stmt->execute();
$getrecord_result = $getrecordsql_stmt->get_result();
$recordRow = $getrecord_result->fetch_assoc();

if (!$recordRow) {
    exit('Record ID not found. Contact Administrator!');
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($record_id)) {

    $patient_id = $_POST['patient_id'] ?? null;
    $diagnosis = $_POST['diagnosis'];
    $feedback = $_POST['feedback'];
    $fname = $_POST["first_name"] ?? null;
    $mi = $_POST["middle_initial"] ?? null;
    $lname = $_POST["last_name"] ?? null;

    if(!isset($_POST['nameToggle'])){
        $patient_id = null;
    }

    // If a record exists, update it
    if($patient_id){

        $update_sql = "UPDATE records SET Patient_ID = ?, Diagnosis = ?, Feedback = ? WHERE RecordID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("issi", $patient_id, $diagnosis, $feedback, $record_id);

    } elseif($fname && $lname) {
        $update_sql = "UPDATE records SET Patient_ID = NULL, fname = ?, middle_initial = ?, lname = ?, Diagnosis = ?, Feedback = ? WHERE RecordID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssi", $fname, $mi, $lname, $diagnosis, $feedback, $record_id);
    }

    
    if ($update_stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $update_stmt->error;
    }
    $update_stmt->close();

}

?>
