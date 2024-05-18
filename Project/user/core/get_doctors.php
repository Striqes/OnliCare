<?php
include '../core/connection.php';

if (isset($_POST['department'])) {
    $department_name = trim($_POST['department']);  // Ensure no trailing spaces or newlines

    $sql = "SELECT doctor.Doctor_ID, user.First_Name, user.Last_Name 
            FROM doctor 
            JOIN user ON doctor.User_ID = user.UserID 
            JOIN department ON doctor.Department_ID = department.Department_ID 
            WHERE department.department_name = ? AND doctor.is_available = 1";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $department_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }

    $doctors = array();
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }

    echo json_encode($doctors);
} else {
    echo json_encode(['error' => 'No department specified']);
}
?>
