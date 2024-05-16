<?php
    include '../core/sessiontimeout.php';
    include '../core/connection.php';

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        exit('User is not logged in.');
    }

    // User Type verification
    if ($_SESSION['UserType'] == 'Patient') {
        header("Location: $indexPath");
        exit();
    }

    $doctor_id = $_SESSION['user_id']; // Get the doctor's ID from the session

    // Retrieve the doctor's availability status
    $availability_sql = "SELECT is_available FROM doctor WHERE Doctor_ID = ?";
    $availability_stmt = $conn->prepare($availability_sql);
    $availability_stmt->bind_param("i", $doctor_id);
    $availability_stmt->execute();
    $availability_result = $availability_stmt->get_result();
    $availability_row = $availability_result->fetch_assoc();
    $is_available = $availability_row['is_available'];

    $availability_stmt->close();

    $sql = "SELECT a.AppointmentID, a.date, u.First_Name, u.Last_Name, a.Status
            FROM appointment a
            JOIN patient p ON a.Patient_ID = p.Patient_ID
            JOIN user u ON p.User_ID = u.UserID
            JOIN doctor d ON a.Doctor_ID = d.Doctor_ID
            WHERE a.Doctor_ID = ? AND d.is_available = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $doctor_id);
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
    <link rel="stylesheet" href="output.css">
</head>
<body class="bg-white">
    <!-- NAV BAR -->
    <header>
        <nav class="bg-white border-b border-gray-200 dark:bg-green-900">
            <div class="max-w-screen-xl mx-auto px-4 py-6 flex items-center justify-between">
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="../onlicarelogo.svg" class="h-10" alt="Logo" />
                    <span class="text-2xl font-semibold whitespace-nowrap dark:text-yellow-400">OnliCare</span>
                </a>

                <!-- MENU BAR -->
                <div class="gap-8 nav-links duration-500 md:static absolute md:min-h-fit bg-green-900 max-h-[15vh] left-0 top-[-100%] md:w-auto w-full flex items-center p-5">
                    <ul class="mx-auto md:ml-0 flex flex-col items-center justify-center gap-8 md:flex-row md:gap-[2vw]">
                        <li>
                            <a href="#" onclick="onToggleMenu(this); scrollToContent('home')" class="block py-2 px-3 text-white rounded hover:bg-transparent hover:text-green-700 dark:text-white dark:hover:text-yellow-300">Appointment List</a>
                        </li>
                        <li>
                            <a href="#" onclick="onToggleMenu(this); scrollToContent('about-us')" class="block py-2 px-3 rounded hover:text-green-700 dark:text-white dark:hover:text-yellow-500">ana pay maikabi?</a>
                        </li>
                        <li>
                            <a href="#" onclick="onToggleMenu(this); scrollToContent('services')" class="block py-2 px-3 text-yellow-900 rounded hover:text-green-700 dark:text-white dark:hover:text-yellow-500">Search</a>
                        </li>
                    </ul>
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
            </div>
        </nav>
    </header>

<main class="bg-gray-100 p-20 pl-24 min-h-screen">
<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="border rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Appointment ID</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Appointment Date</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Patient Name</th>
                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Status</th>
                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='px-6 py-4 text-start whitespace-nowrap text-sm font-medium text-gray-800'>{$row['AppointmentID']}</td>";
                    echo "<td class='px-6 py-4 text-start whitespace-nowrap text-sm font-medium text-gray-800'>{$row['date']}</td>";
                    echo "<td class='px-6 py-4 text-start whitespace-nowrap text-sm text-gray-800'>{$row['First_Name']} {$row['Last_Name']}</td>";
                    echo "<td class='px-6 py-4 text-end whitespace-nowrap text-sm text-gray-800'>{$row['Status']}</td>";
                    echo "<td class='px-6 py-4 text-end whitespace-nowrap text-sm font-medium'>";
                    echo "<a href='#' class='text-green-500 hover:text-green-700'>Approve</a> | <a href='#' class='text-red-500 hover:text-red-700'>Decline</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>




    

    <?php 
        if(!isset($_SESSION["user_id"])){
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

    </main>
   


    <!-- FOOTER -->
    <footer class="bg-white shadow-md dark:bg-green-900">
        <div class="max-w-screen-xl mx-auto p-4 md:p-8 flex flex-col md:flex-row items-center justify-between">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse mb-4 md:mb-0">
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
                    <li>
                        <a href="doctorprofile.php" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Profile</a>
                    </li>
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
