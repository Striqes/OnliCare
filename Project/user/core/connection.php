<?php
$conn = new mysqli("localhost","root","","onlicare");
session_start();

$url_root = 'http://' . $_SERVER['HTTP_HOST'] . '/onlicare/';

$indexPath = $url_root . "Project/index.php";
$login = $url_root . "Project/user/login.php";
$doctorIndex =  $url_root . "Project/user/doctor/doctorindex.php";
$patientIndex =  $url_root . "Project/user/patient/patientindex.php";
$adminIndex =  $url_root . "Project/user/admin/admin.php";

$appointment = $url_root. "Project/user/patient/appointment.php";
$updateProfile = $url_root. "Project/user/profileBackend/updateProfile.php";

if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}

?>
