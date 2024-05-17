<?php
include '../core/connection.php';

$searchTerm = $_GET['searchTerm'];

$sql = "SELECT p.Patient_ID, u.First_Name, u.Last_Name
        FROM patient p
        JOIN user u ON p.User_ID = u.UserID
        WHERE u.First_Name LIKE ? OR u.Last_Name LIKE ? OR p.Patient_ID = ?";
$stmt = $conn->prepare($sql);
$searchTermWithWildcards = '%' . $searchTerm . '%';
$stmt->bind_param("ssi", $searchTermWithWildcards, $searchTermWithWildcards, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div class='flex justify-between items-center p-2 hover:bg-gray-200 cursor-pointer'>";
    echo "<span>" . $row["First_Name"]. " " . $row["Last_Name"]. "</span>";
    echo "<a href='medical_record.php?patient_id=" . $row["Patient_ID"] . "' class='text-blue-500 hover:underline'>View Medical Record</a>";
    echo "</div>";
}
?>