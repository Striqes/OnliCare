<?php
include '../core/connection.php';
$conn = new mysqli("localhost","root","","onlicare");
if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
}

$department_name = $_POST['department'];
$sql = "SELECT doctor.Doctor_ID, user.First_Name, user.Last_Name FROM doctor JOIN user ON doctor.User_ID = user.UserID JOIN department ON doctor.Department_ID = department.Department_ID WHERE department.department_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $department_name);
$stmt->execute();
$result = $stmt->get_result();
$doctors = array();
while($row = $result->fetch_assoc()) {
        $doctors[] = $row;
}
echo json_encode($doctors);
?>
