<?php
include '../core/sessiontimeout.php';
include '../core/connection.php';
$conn = new mysqli("localhost","root","","onlicare");
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    
<div class="mx-14 mt-10 border-2 border-yellow-400 rounded-lg">
  <div class="mt-10 text-center font-bold">Contact Us</div>
  <div class="mt-3 text-center text-4xl font-bold">Make an Appointment</div>
  <div class="p-8">
  <form action="process_appointment.php" method="POST">
  <div class="flex flex-col gap-4 p-4 bg-green-800 rounded shadow">
        <?php
       
        $user_id = $_SESSION['user_id']; 

        $query = "SELECT First_Name, Middle_Initial, Last_Name FROM user WHERE UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id); // "i" indicates the variable type is integer

        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        ?>
        <div class="text-lg font-semibold text-yellow-400">
            <?php echo "FULL NAME: " . strtoupper($user['First_Name'] . " " . $user['Middle_Initial'] . ". " . $user['Last_Name']); ?>
        </div>
    </div>

    <div class="my-6 flex gap-4">
        <select name="select_department" id="department" onchange="updateDoctors(this.value)" class="block w-1/2 rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
            <option class="font-semibold text-slate-300">Please Select department</option>
            <?php
            // Assuming you have a valid connection $conn

            $query = "SHOW COLUMNS FROM department LIKE 'department_name'";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();

            $type = $row['Type'];
            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
            $enum = explode("','", $matches[1]);

            foreach ($enum as $value) {
                echo "<option value='" . $value . "'>" . $value . "</option>";
            }
            ?>
        </select>
        <select name="select_doctor" id="doctor" class="block w-1/2 rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
            <option class="font-semibold text-slate-300">Please Select doctor</option>
        </select>
    </div>

        <div class="my-6 flex gap-4">
        <input type="date" name="date" id="date" class="block w-1/2 rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
        <input type="time" name="time" id="time" class="block w-1/2 rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
        </div>
        <div class="">
        <textarea name="textarea" id="text" cols="30" rows="10" class="mb-10 h-40 w-full resize-none rounded-md border border-slate-300 p-5 font-semibold text-gray-300">Message</textarea>
        </div>
        <div class="text-center">
        <a class="cursor-pointer rounded-lg bg-blue-700 px-8 py-5 text-sm font-semibold text-white">Book Appoinment</a>
        </div>
    </form>
  </div>
</div>

</body>
</html>