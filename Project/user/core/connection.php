<?php
$conn = new mysqli("localhost","root","","onlicare");
session_start();

$url_root = 'http://' . $_SERVER['HTTP_HOST'] . '/onlicare/';

$indexPath = $url_root . "Project/index.php";
$login = $url_root . "Project/user/login.php";
$logout = $url_root . "Project/user/core/logout.php";
$doctorIndex =  $url_root . "Project/user/doctor/doctorindex.php";
$patientIndex =  $url_root . "Project/user/patient/patientindex.php";
$adminIndex =  $url_root . "Project/user/admin/admin.php";

$appointment = $url_root. "Project/user/patient/appointment.php";
$ViewAppointment = $url_root. "Project/user/patient/viewappointment.php";
$updateProfile = $url_root. "Project/user/profileBackend/updateProfile.php";
$defProfile = $url_root. "Project/user/profile.php";
$doctorProfile = $url_root. "Project/user/doctor/doctorprofile.php";
$medRecords = $url_root. "Project/user/doctor/medical_record.php";
$patientRecords = $url_root . "Project/user/patient/records.php";

if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}

?>
