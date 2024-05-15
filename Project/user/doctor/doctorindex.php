<?php
    include '../core/sessiontimeout.php';
    include '../core/connection.php';

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        exit('User is not logged in.');
    }

    // User Type verifcation
    if($_SESSION['UserType'] == 'Patient'){
        header("Location: $indexPath");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
    }


    $doctor_id = $_SESSION['user_id']; // or however you get the doctor's ID

    $sql = "SELECT a.AppointmentID, a.date, u.First_Name, u.Last_Name, a.Status
    FROM appointment a
    JOIN patient p ON a.Patient_ID = p.Patient_ID
    JOIN user u ON p.User_ID = u.UserID
    WHERE a.Doctor_ID = $doctor_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnliCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- HUMBRUGER -->
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- AJAX CDN -->
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

                
                <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-3xl cursor-pointer md:hidden"></ion-icon>
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
                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800'>" . $row["AppointmentID"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>" . $row["date"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>" . $row["First_Name"] . " " . $row["Last_Name"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>" . $row["Status"] . "</td>"; // Added this line
                        echo "<td class='px-6 py-4 whitespace-nowrap text-end text-sm font-medium'>";
                       echo "<button type='button' class='inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-green-600 hover:text-green-800 disabled:opacity-50 disabled:pointer-events-none'>Approve</button>"; 
                        echo "<button type='button' class='inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none'>Cancel</button>";
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>No appointments found</td></tr>"; // Changed colspan from 4 to 5
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
                        <a href="../profile.php" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Profile</a>
                    </li>
                    <li>
                        <button type="submit" onclick="logout()" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Log out</button>
                    </li>
                `;
            }
        }

        function logout() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "user/core/logout.php", true);
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
