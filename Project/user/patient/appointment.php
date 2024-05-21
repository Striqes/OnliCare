<?php
include '../core/connection.php';
//include '../core/sessiontimeout.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: $indexPath");
    exit();
}

// User Type verification
if ($_SESSION['UserType'] !== 'Patient') {
    header("Location: $indexPath");
    exit();
}

/* if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['select_doctor']) && isset($_POST['date']) && isset($_POST['textarea'])) {
        
        $user_id = $_SESSION['user_id'];
        $doctor_id = $_POST['select_doctor'];
        $date = $_POST['date'];
        $message = $_POST['textarea'];

        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');
        $noonTime = "12:00:00";

        if ($date < $currentDate) {
            echo '<script>alert("Cannot book an appointment in the past."); window.location.href = "appointment.php";</script>';
            exit();
        }

        // Check if the date is today and the current time is past 12 PM
        if ($date == $currentDate && $currentTime > $noonTime) {
            echo '<script>alert("Cannot book an appointment for today please select another day."); window.location.href = "appointment.php"; </script>';
            exit();
        }

        // Check if the date is more than a month in the future
        $oneMonthFromNow = date('Y-m-d', strtotime('+1 month'));
        if ($date > $oneMonthFromNow) {
            echo '<script>alert("Cannot book an appointment more than a month in advance."); window.location.href = "appointment.php"; </script>';
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
} */

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
    <title>OnliCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <!-- NAV BAR -->
    <header>
        <nav class="bg-white border-b border-gray-200 dark:bg-green-900">
            <div class="max-w-screen-xl mx-auto px-4 py-6 flex items-center justify-between">
                <a href="<?php echo $indexPath;?>" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="<?php echo "$url_root" . "assets/onlicarelogo.svg";?>" class="h-10" alt="Logo" />
                    <span class="text-2xl font-semibold whitespace-nowrap dark:text-yellow-400">OnliCare</span>
                </a>

                <!-- MENU BAR -->
                <div class="gap-8 nav-links duration-500 md:static absolute md:min-h-fit bg-green-900 max-h-[15vh] left-0 top-[-100%] md:w-auto w-full flex items-center p-5">
                    <ul id="loginButtons" class="mx-auto md:ml-0 flex flex-col items-center justify-center gap-8 md:flex-row md:gap-[2vw]">
                        <li>
                            <a href="user/login.php" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Log in</a>
                        </li>
                        <li>
                            <a href="user/signup.php" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Sign up</a>
                        </li>
                    </ul>
        
                </div>
            </div>
        </nav>
    </header>

    <main class="bg-gray-100 p-20 pl-24 min-h-screen">
            
  <div class="mt-3 text-center text-4xl font-bold">Make an Appointment</div>
  <div class="p-8">
  <form action="handle_appointment.php" method="post">
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

    </main>
    
    <!-- FOOTER -->
    <footer class="bg-white shadow-md dark:bg-green-900">
        <div class="max-w-screen-xl mx-auto p-4 md:p-8 flex flex-col md:flex-row items-center justify-between">
            <a href="<?php echo $indexPath;?>" class="flex items-center space-x-3 rtl:space-x-reverse mb-4 md:mb-0">
                <img src="..\assets\onlicarelogo.svg" class="h-8" alt="Logo">
                <span class="text-2xl font-semibold whitespace-nowrap dark:text-white">OnliCare</span>
            </a>
            <ul class="flex flex-wrap justify-center md:justify-end items-center space-x-4">
                <li>
                    <a href="#" onclick="onToggleMenu(this); scrollToContent('about-us')" class="hover:underline text-yellow-300">About</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-yellow-200 dark:border-yellow-700">
        <span class="block text-sm text-gray-500 text-center dark:text-gray-400">© 2023 <a href="#" class="hover:underline">OnliCare™</a>. All Rights Reserved.</span>
    </footer>

    <!-- ScrollDown Script -->
    <script>
        function scrollToContent(id) {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: "smooth" });
            }
        }

        // Check for section ID in URL and scroll to it
        window.addEventListener('DOMContentLoaded', (event) => {
            const hash = window.location.hash.substring(1); // Remove the '#' from the hash
            if (hash) {
                scrollToContent(hash);
            }
        });

        // Function for Menu Button on smaller dimension devices
        const navLinks = document.querySelector('.nav-links')
        function onToggleMenu(e){
            e.name = e.name === 'menu' ? 'close' : 'menu'
            navLinks.classList.toggle('top-[9%]')
            // navLinks.classList.toggle('hidden');
        }

        function changeLoginButtonsContent() {
            var loginButtons = document.getElementById("loginButtons");
            if (loginButtons) {
                loginButtons.innerHTML = `


                    <?php 
                    
                        if(!isset($_SESSION['user_id'])){
                            exit();
                        } else {
                            if($_SESSION['UserType'] == 'Patient'){
                                echo
                                '<li>
                                    <a href="'. $appointment .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Make an Appointment</a>
                                </li>';
                                if($_SESSION['UserType'] == 'Patient'){
                                    echo
                                    '<li>
                                        <a href="'. $ViewAppointment .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">View Appointments</a>
                                    </li>
                                    <li>
                                        <a href="'. $patientRecords .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">View Records</a>
                                    </li>';
                                }
                            }else if($_SESSION['UserType'] == 'Doctor'){
                                echo
                                '<li>
                                    <a href="'. $doctorIndex .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Dashboard</a>
                                </li>
                                
                                <li>
                                    <a href="'. $doctorProfile .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Profile</a>
                                </li>
                                ';
                            }else if($_SESSION['UserType'] == 'Admin'){
                                echo
                                '<li>
                                    <a href="'. $adminIndex .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Admin Panel</a>
                                </li>';
                            } else {
                                exit('Unknown User Type');
                            }

                            if($_SESSION['UserType'] == 'Doctor'){

                            } else {
                                echo
                                '<li>
                                    <a href="'. $defProfile .'" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Profile</a>
                                </li>';
                            }
                        }

                    ?>

                    <li>
                        <button type="submit" onclick="logout()" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Log out</button>
                    </li>
                `;
            }
        }


        function logout() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../core/logout.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    window.location.href = "<?php echo $indexPath;?>"; 
                }
            };
            xhr.send("action=logout");
            location.reload();
        }

    </script>

<?php 
    if(!$_SESSION["user_id"]){
        // nothing happens
    } else {
        $user_id = $_SESSION['user_id']; 
        $query = "SELECT First_Name, Middle_Initial, Last_Name FROM user WHERE UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);

        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        echo '<script>changeLoginButtonsContent();</script>';
    }

?>

</body>
</html>
