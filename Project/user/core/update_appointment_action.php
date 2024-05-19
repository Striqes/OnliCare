<?php
include '../core/sessiontimeout.php';
include '../core/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Function to respond with JSON and exit
function respond($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

// Check if required POST parameters are set
if (!isset($_POST['appointmentID']) || !isset($_POST['action'])) {
    respond(false, 'Invalid request.');
}

$appointmentID = $_POST['appointmentID'];
$action = $_POST['action'];

// Validate action
if ($action !== 'Approved' && $action !== 'Rejected') {
    respond(false, 'Invalid action.');
}

try {
    if ($action === 'Approved') {
        // Get the message from the request
        $message = $_POST['message'];
    
        // Update the action to "Approved" and add the message
        $update_sql = "UPDATE appointment SET Action = 'Approved', Status = 'Approved', Message = ? WHERE AppointmentID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $message, $appointmentID);
        if ($stmt->execute()) {
            respond(true, 'Appointment has been approved.');
        } else {
            respond(false, 'Failed to approve appointment.');
        }
    } elseif ($action === 'Rejected') {
        // Check if the reason parameter is set
        if (!isset($_POST['reason'])) {
            respond(false, 'Reason not provided.');
        }

        $reason = $_POST['reason'];
        // Update the appointment in the database to be invisible and set its status to 'Rejected'
        $update_sql = "UPDATE appointment SET is_visible = 0, Status = 'Rejected', Message = ? WHERE AppointmentID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $reason, $appointmentID);
    }

    // Execute the statement
    if ($stmt->execute()) {
        respond(true, 'Appointment action updated successfully.');
    } else {
        respond(false, 'Failed to update appointment action.');
    }

    $stmt->close();
} catch (Exception $e) {
    respond(false, 'Error: ' . $e->getMessage());
}

$conn->close();
?>
