<?php
include '../core/sessiontimeout.php';
include '../core/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: $indexPath");
    exit();
}

// Get the Patient's ID based on the logged-in user ID
$getPatientsql = "SELECT Patient_ID FROM Patient WHERE User_ID = ?";
$getPatientsql_stmt = $conn->prepare($getPatientsql);
$getPatientsql_stmt->bind_param("i", $_SESSION['user_id']);
$getPatientsql_stmt->execute();
$getPatient_result = $getPatientsql_stmt->get_result();
$PatientRow = $getPatient_result->fetch_assoc();

if (!$PatientRow) {
    exit('Patient ID not found. Contact Administrator!');
} else {
    $patient_id = $PatientRow['Patient_ID'];
}

// Retrieve appointments for the Patient
    $sql = "
        SELECT 
            r.RecordID, 
            IFNULL(r.Patient_ID, NULL) AS Patient_ID, 
            r.Doctor_ID, 
            r.Diagnosis, 
            r.Feedback, 
            CASE 
                WHEN r.Patient_ID IS NULL THEN SUBSTRING_INDEX(SUBSTRING_INDEX(IFNULL(r.Patient_ID, CONCAT(r.fname, ' ', IFNULL(r.middle_initial, ''), ' ', r.lname)), ' ', 1), ' ', -1) 
                ELSE pUser.First_Name 
            END AS Patient_First_Name, 
            CASE 
                WHEN r.Patient_ID IS NULL THEN 
                    CASE 
                        WHEN LENGTH(IFNULL(r.Patient_ID, CONCAT(r.fname, ' ', IFNULL(r.middle_initial, ''), ' ', r.lname))) - LENGTH(REPLACE(IFNULL(r.Patient_ID, CONCAT(r.fname, ' ', IFNULL(r.middle_initial, ''), ' ', r.lname)), ' ', '')) > 1 THEN 
                            SUBSTRING_INDEX(SUBSTRING_INDEX(IFNULL(r.Patient_ID, CONCAT(r.fname, ' ', IFNULL(r.middle_initial, ''), ' ', r.lname)), ' ', 2), ' ', -1)
                        ELSE 
                            NULL
                    END
                ELSE pUser.Middle_Initial 
            END AS Patient_Middle_Initial, 
            CASE 
                WHEN r.Patient_ID IS NULL THEN 
                    SUBSTRING_INDEX(IFNULL(r.Patient_ID, CONCAT(r.fname, ' ', IFNULL(r.middle_initial, ''), ' ', r.lname)), ' ', -1) 
                ELSE pUser.Last_Name 
            END AS Patient_Last_Name, 
            dUser.First_Name AS Doctor_First_Name, 
            dUser.Last_Name AS Doctor_Last_Name
        FROM 
            records r
        LEFT JOIN 
            patient p ON r.Patient_ID = p.Patient_ID
        LEFT JOIN 
            user pUser ON p.User_ID = pUser.UserID
        JOIN 
            doctor d ON r.Doctor_ID = d.Doctor_ID
        JOIN 
            user dUser ON d.User_ID = dUser.UserID
        WHERE
            r.Patient_ID = ? AND r.visible = 1;
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

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

        <div class="mb-8">
            <a href="add_record.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Record</a>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Record ID</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Diagnosis</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Feedback</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Patient ID</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Patient Name</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Doctor ID</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Recorded By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            <?php
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td class='px-6 py-4 text-start whitespace-nowrap text-sm font-medium text-gray-800'>{$row['RecordID']}</td>
                        <td class='px-6 py-4 text-start whitespace-nowrap text-sm text-gray-800'>{$row['Diagnosis']}</td>
                        <td class='px-6 py-4 text-start whitespace-nowrap text-sm text-gray-800'>{$row['Feedback']}</td>
                        <td class='px-6 py-4 text-start whitespace-nowrap text-sm text-gray-800'>";

                    if(isset($row['Patient_ID'])){ 
                        echo $row['Patient_ID']; 
                    } else {
                        echo "Not Registered";
                    }

                    echo "</td>
                        <td class='px-6 py-4 text-start whitespace-nowrap text-sm text-gray-800'>{$row['Patient_First_Name']} {$row['Patient_Middle_Initial']}. {$row['Patient_Last_Name']}</td>
                        <td class='px-6 py-4 text-start whitespace-nowrap text-sm text-gray-800'>{$row['Doctor_ID']}</td>
                        <td class='px-6 py-4 text-start whitespace-nowrap text-sm text-gray-800'>Dr. {$row['Doctor_First_Name']} {$row['Doctor_Last_Name']}</td>
                    </tr>";
                }
                
            ?>

            </tbody>
        </table>
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

        function deleteRecord(recordID) {
            if (confirm("Are you sure you want to delete this record?")) {
                // Perform AJAX request to delete the record
                // Example using jQuery AJAX
                $.ajax({
                    url: 'deleteRecord.php',
                    type: 'POST',
                    data: { record_id: recordID },
                    success: function(response) {
                        // Handle success response
                        // For example, you can redirect to a different page
                        alert(response);
                        console.error(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(error);
                    }
                });
            }
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
