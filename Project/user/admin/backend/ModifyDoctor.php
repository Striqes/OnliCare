<?php

include '../../core/connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $doctorID = $_POST["doctorID"];
    $department_id = $_POST["department_id"];
    $specialization_id = $_POST["specialization_id"];

    // Confirm if Doctor with ID Exists
    $searchsql = "SELECT * FROM doctor WHERE Doctor_ID = ?";
    $stmt = $conn->prepare($searchsql);
    $stmt->bind_param("i", $doctorID);
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows <= 0){
        exit("Doctor with Given ID not found.");
    }

    // Prepare and execute doctor update
    $sql_update_doctor = "UPDATE doctor SET Department_ID = ?, Specialization_ID = ? WHERE Doctor_ID = ?";
    $stmt_update_doctor = $conn->prepare($sql_update_doctor);
    $stmt_update_doctor->bind_param("iii", $department_id, $specialization_id, $doctorID);

    // Execute doctor update
    if (!$stmt_update_doctor->execute()) {
        echo "Error updating Doctor: " . $stmt_update_doctor->error;
        $stmt_update_doctor->close();
        exit();
    } else {
        echo "Successfully Edited Doctor Information";
    }

    // Close prepared statements
    $stmt_update_doctor->close();
    exit();
}

?>
