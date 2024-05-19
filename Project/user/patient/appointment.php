<?php
include '../core/sessiontimeout.php';
include '../core/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: $indexPath");
}

if($_SESSION['UserType'] == 'Doctor'){
    header("Location: $indexPath");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['select_doctor']) && isset($_POST['date']) && isset($_POST['textarea'])) {
        
        $user_id = $_SESSION['user_id'];
        $doctor_id = $_POST['select_doctor'];
        $date = $_POST['date'];
        $message = $_POST['textarea'];

        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');
        $noonTime = "12:00:00";

        if ($date < $currentDate) {
            echo '<script>alert("Cannot book an appointment in the past.");</script>';
            exit();
        }

        // Check if the date is today and the current time is past 12 PM
        if ($date == $currentDate && $currentTime > $noonTime) {
            echo '<script>alert("Cannot book an appointment for today please select another day.");</script>';
            exit();
        }

        // Check if the date is more than a month in the future
        $oneMonthFromNow = date('Y-m-d', strtotime('+1 month'));
        if ($date > $oneMonthFromNow) {
            echo '<script>alert("Cannot book an appointment more than a month in advance.");</script>';
            exit();
        }


        if (empty($doctor_id) || empty($date) || empty($message)) {
            exit('All form fields are required.');
        }

        // Get the Patient_ID using the User_ID
        $sql_get_patient_id = "SELECT Patient_ID FROM patient WHERE User_ID = ?";
        $stmt_get_patient_id = $conn->prepare($sql_get_patient_id);
        $stmt_get_patient_id->bind_param("i", $user_id);
        $stmt_get_patient_id->execute();
        $result_get_patient_id = $stmt_get_patient_id->get_result();
        if ($result_get_patient_id->num_rows === 0) {
            exit('User is not a patient.');
        }
        $row = $result_get_patient_id->fetch_assoc();
        $patient_id = $row['Patient_ID'];
        $stmt_get_patient_id->close();
    
        $sql = "INSERT INTO appointment (Doctor_ID, Patient_ID, date, Message) VALUES (?, ?, ?,? )";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $doctor_id, $patient_id, $date, $message);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            exit('Failed to make an appointment.');
        } else {
            $_SESSION['message'] = "Appointment successfully made. Please Wait for approval of Doctor.";
            header("Location: appointment.php"); 
            exit();
        }
    } else {
        echo 'All form fields are required.';
    }
}

    if (isset($_SESSION['message'])) {
        echo '<script type="text/javascript">alert("' . $_SESSION['message'] . '");</script>';
        unset($_SESSION['message']);
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
  <form  method="POST">
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
            $sql = "SELECT department_name FROM department";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['department_name'].'">'.$row['department_name'].'</option>';
                }
            } else {
                echo '<option>No departments found</option>';
            }
            ?>
        </select>
        <select name="select_doctor" id="doctor" class="block w-1/2 rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
        <option class="font-semibold text-slate-300">Please Select doctor</option>
        </select>
    </div>
    <script>
function updateDoctors() {
    var deptName = $("#department").val().trim();  // Ensure no trailing spaces or newlines

    $.ajax({
        url: '../core/get_doctors.php',
        type: 'POST',
        data: { department: deptName },
        dataType: 'json',
        success: function(response) {
            console.log(response);  // Log the response for debugging
            var len = response.length;
            $("#doctor").empty(); // Clear the existing options

            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var name = "Dr. " + response[i]['First_Name'] + " " + response[i]['Last_Name'];
                    var id = response[i]['Doctor_ID'];
                    $("#doctor").append("<option value='" + id + "'>" + name + "</option>");
                }
            } else {
                // Add a default option if no doctors are found
                $("#doctor").append("<option value=''>No available doctors</option>");
            }
        },
        error: function(xhr, status, error) {
            console.error("An error occurred while fetching doctors: ", status, error);
            alert("An error occurred while fetching doctors. Please try again later.");
        }
    });
}
</script>





    <div class="my-6 flex gap-4">     
    <input type="date" name="date" class="block w-full rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" required>
    </div>
        <div class="">
        <textarea name="textarea" id="text" cols="30" rows="10" class="mb-10 h-40 w-full resize-none rounded-md border border-slate-300 p-5 font-semibold text-black" placeholder="Message"></textarea>
        </div>
        <div class="text-center">
        <button type="submit" class="rounded-lg bg-blue-700 px-8 py-5 text-sm font-semibold text-white">Book Appointment</button>
        </div>
    </form>
  </div>
</div>

</body>
</html>