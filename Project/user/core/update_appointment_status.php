<?php
include '../core/sessiontimeout.php';
include '../core/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Check if required POST parameters are set
if (isset($_POST['appointmentID']) && isset($_POST['status'])) {
    $appointmentID = $_POST['appointmentID'];
    $status = $_POST['status'];

    // Update the status of the appointment
    $update_sql = "UPDATE appointment SET Status = ? WHERE AppointmentID = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $appointmentID);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>
