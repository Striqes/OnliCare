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

$update_sql = "UPDATE records SET visible = 0";
$update_stmt = $conn->prepare($update_sql);

if ($update_stmt->execute()) {
    echo "Successfully Deleted Record";
} else {
    echo "Error: " . $update_stmt->error;
}
$update_stmt->close();

?>