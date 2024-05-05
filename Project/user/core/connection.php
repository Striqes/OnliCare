<?php
$conn = new mysqli("localhost","root","","onlicare");


if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}


?>