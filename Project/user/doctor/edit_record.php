<?php
include '../core/sessiontimeout.php';
include '../core/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: $indexPath");
    exit();
}

// User Type verification
if ($_SESSION['UserType'] !== 'Doctor') {
    header("Location: $indexPath");
    exit();
}

$record_id = isset($_GET['record_id']) ? $_GET['record_id'] : "Bruh";

if($record_id == "Bruh"){
    header("Location: $doctorIndex");
}

// Get the doctor's ID based on the logged-in user ID
$getDoctorsql = "SELECT Doctor_ID FROM doctor WHERE User_ID = ?";
$getDoctorsql_stmt = $conn->prepare($getDoctorsql);
$getDoctorsql_stmt->bind_param("i", $_SESSION['user_id']);
$getDoctorsql_stmt->execute();
$getDoctor_result = $getDoctorsql_stmt->get_result();
$doctorRow = $getDoctor_result->fetch_assoc();

if (!$doctorRow) {
    exit('Doctor ID not found. Contact Administrator!');
} else {
    $doctor_id = $doctorRow['Doctor_ID'];
}

// Retrieve the doctor's availability status
$availability_sql = "SELECT is_available FROM doctor WHERE Doctor_ID = ?";
$availability_stmt = $conn->prepare($availability_sql);
$availability_stmt->bind_param("i", $doctor_id);
$availability_stmt->execute();
$availability_result = $availability_stmt->get_result();
$availability_row = $availability_result->fetch_assoc();

// Check if a row was returned before accessing the array offset
$is_available = $availability_row ? $availability_row['is_available'] : 0;
$availability_stmt->close();

$searchRecord = "SELECT * FROM records WHERE RecordID = ?";
$search_stmt = $conn->prepare($searchRecord);
$search_stmt->bind_param("i", $record_id);
$search_stmt->execute();
$search_result = $search_stmt->get_result();
$Records = $search_result->fetch_assoc();

if(isset($Records['Patient_ID'])){
    $patient_id = $Records['Patient_ID'];
} else {
    $patient_id = null;
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
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
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
                
                <label class="flex items-center relative w-max cursor-pointer select-none">
                    <span class="text-lg font-bold mr-3 text-yellow-300">Doctor</span>
                    <input id="toggle" type="checkbox" class="appearance-none transition-colors cursor-pointer w-14 h-7 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black focus:ring-blue-500 bg-<?php echo $is_available ? 'green' : 'yellow'; ?>-500" <?php echo $is_available ? 'checked' : ''; ?> />
                    <span id="off" class="absolute font-medium text-xs uppercase right-1 text-white" style="display: <?php echo $is_available ? 'none' : 'block'; ?>;"> IN </span>
                    <span id="on" class="absolute font-medium text-xs uppercase right-8 text-white" style="display: <?php echo $is_available ? 'block' : 'none'; ?>;"> OUT </span>
                    <span id="circle" class="w-7 h-7 right-7 absolute rounded-full transform transition-transform bg-gray-200" style="transform: <?php echo $is_available ? 'translateX(1.75rem)' : 'translateX(0)'; ?>;"></span>
                </label>
                <script>
                    document.getElementById('toggle').addEventListener('change', function() {
                        var toggle = document.getElementById('toggle');
                        var circle = document.getElementById('circle');
                        var onText = document.getElementById('on');
                        var offText = document.getElementById('off');
                        var doctorId = <?php echo $_SESSION['user_id']; ?>; // Get the doctor ID from PHP session

                        if (toggle.checked) {
                            circle.style.transform = 'translateX(1.75rem)';
                            toggle.classList.replace('bg-yellow-500', 'bg-green-500');
                            onText.style.display = 'block';
                            offText.style.display = 'none';
                        } else {
                            circle.style.transform = 'translateX(0)';
                            toggle.classList.replace('bg-green-500', 'bg-yellow-500');
                            onText.style.display = 'none';
                            offText.style.display = 'block';
                        }

                        // Send AJAX request to update_doctor_availability.php
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_doctor_availability.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.send('doctor_id=' + encodeURIComponent(doctorId) + '&isAvailable=' + encodeURIComponent(toggle.checked ? 1 : 0));
                    });
                </script>
                       
        <div class="relative">
            <input type="text" id="search" class="block w-full py-2 px-3 rounded" placeholder="Search..." onkeyup="onSearch(this.value)">
            <div id="searchResults" class="absolute left-0 mt-1 w-full z-10 bg-white border border-gray-300 rounded shadow-lg overflow-auto max-h-60" style="display: none;"></div>
        </div>
        <script>
            function onSearch(searchTerm) {
                if(searchTerm.length > 0) {
                    $.ajax({
                        url: '../core/search_patients.php',
                        type: 'get',
                        data: { searchTerm: searchTerm },
                        success: function(response) {
                            $('#searchResults').html(response);
                            $('#searchResults').show();
                        }
                    });
                } else {
                    $('#searchResults').hide();
                }
            }

            // Hide the dropdown when clicked outside
            $(document).on('click', function (e) {
                if ($(e.target).closest("#search").length === 0) {
                    $('#searchResults').hide();
                }
            });
        </script>     
            </div>
        </nav>
    </header>

    <main class="bg-gray-100 p-20 pl-24 min-h-screen">

    <div class="mx-14 mt-10 border-2 border-yellow-400 rounded-lg pl-28 pr-28">
            <div class="p-8">
            <form id="recordForm" method="POST">
                <input type="hidden" name="record_id" value="<?php echo isset($_GET['record_id']) ? htmlspecialchars($_GET['record_id']) : ''; ?>">
                <div class="flex flex-col gap-4 p-4 bg-green-800 rounded shadow">
                    <div class="text-lg font-semibold text-yellow-400">
                        Modify Medical Record
                    </div>
                </div>

                <div class="my-6 flex flex-col gap-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="nameToggle" name="nameToggle" class="form-checkbox"<?php if($patient_id) echo "checked" ?>>
                        <span class="ml-2">Patient is a registered user.</span>
                    </label>
                    <div id="patientIdInput" class="patient-input"<?php if(!$patient_id) echo ' style="display: none;"'; ?>>
                        <input type="text" name="patient_id" id="patient_id" class="block rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" placeholder="Enter Patient ID" value="<?php if($patient_id) echo $patient_id ?>" >
                    </div>
                    <div id="patientNameInput" class="patient-input"<?php if($patient_id) echo ' style="display: none;"'; ?>>
                        <input type="text" name="first_name" id="fname" class="block rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm " <?php 
                                                                                                                                                                                                                                                                        if (!$patient_id) {
                                                                                                                                                                                                                                                                            // If $patient_id does not exist, set the value attribute
                                                                                                                                                                                                                                                                            echo "value=\"" . htmlspecialchars($Records['fname']) . "\"";
                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                            // If $patient_id exists, set the placeholder attribute
                                                                                                                                                                                                                                                                            echo "placeholder=\"Enter First Name\"";
                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                        ?>>

                        <input type="text" name="middle_initial" id="mi" class="block rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm mt-4" <?php 
                                                                                                                                                                                                                                                                        if (!$patient_id) {
                                                                                                                                                                                                                                                                            // If $patient_id does not exist, set the value attribute
                                                                                                                                                                                                                                                                            echo "value=\"" . htmlspecialchars($Records['middle_initial']) . "\"";
                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                            // If $patient_id exists, set the placeholder attribute
                                                                                                                                                                                                                                                                            echo "placeholder=\"Enter Middle Initial\"";
                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                        ?>>

                        <input type="text" name="last_name" id="lname" class="block rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm mt-4" <?php 
                                                                                                                                                                                                                                                                        if (!$patient_id) {
                                                                                                                                                                                                                                                                            // If $patient_id does not exist, set the value attribute
                                                                                                                                                                                                                                                                            echo "value=\"" . htmlspecialchars($Records['lname']) . "\"";
                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                            // If $patient_id exists, set the placeholder attribute
                                                                                                                                                                                                                                                                            echo "placeholder=\"Enter Last Name\"";
                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                        ?>>
                    </div>
                    <input type="text" name="diagnosis" class="block rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="<?php echo $Records['Diagnosis']?>">
                    <textarea name="feedback" class="block rounded-md border border-slate-300 bg-white px-3 py-4 font-semibold text-gray-500 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"><?php echo $Records['Feedback']?></textarea>
                </div>
                <div class="text-center">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Modify Record</button>
                </div>
            </form>

            </div>
    </div>
            

    </main>
   


    <!-- FOOTER -->
    <footer class="bg-white shadow-md dark:bg-green-900">
        <div class="max-w-screen-xl mx-auto p-4 md:p-8 flex flex-col md:flex-row items-center justify-between">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse mb-4 md:mb-0">
                <img src="<?php echo "$url_root" . "assets/onlicarelogo.svg";?>" class="h-8" alt="Logo">
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

        $(document).ready(function() {
            $('#recordForm').submit(function(event) {
                // Get form data
                var formData = $(this).serialize();

                // Send AJAX request
                $.ajax({
                    type: 'POST',
                    url: 'modifyRecord.php',
                    data: formData,
                    success: function(response) {
                        console.log(response); // Log the response from the server

                        // Handle success or error response here
                        if (response.trim() === 'success') {
                            // Do something if the operation was successful
                            alert('Record Edited successfully');
                        } else {
                            // Do something if there was an error
                            alert('Error: ' + response);
                            console.log('Error: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX error
                        console.error(xhr.responseText);
                    }
                });
            });
        });

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

        $(document).ready(function() {
            $('#nameToggle').change(function() {
                if (this.checked) {
                    document.getElementById("patient_id").required = true;
                    $('#patientIdInput').show();
                    $('#patientNameInput').hide();
                    document.getElementById("1");
                    document.getElementById("fname").required = false;
                    document.getElementById("mi").required = false;
                    document.getElementById("lname").required = false;

                } else {
                    document.getElementById("patient_id").value = '';
                    document.getElementById("fname").required = true;
                    document.getElementById("mi").required = true;
                    document.getElementById("lname").required = true;
                    $('#patientNameInput').show();
                    $('#patientIdInput').hide();
                    document.getElementById("patient_id").required = false;
                }
            });
        });

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
