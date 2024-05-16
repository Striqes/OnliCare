<?php
include '../core/sessiontimeout.php';
include '../core/connection.php';

if (!isset($_SESSION['user_id'])) {
    exit('User is not logged in.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctorId = $_SESSION['user_id'];
    $isAvailable = isset($_POST['isAvailable']) ? intval($_POST['isAvailable']) : 0;

    $sql = "UPDATE doctor SET is_available = ? WHERE User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $isAvailable, $doctorId);

    if ($stmt->execute()) {
        echo "Availability updated successfully.";
    } else {
        echo "Error updating availability: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>
