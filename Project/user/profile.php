<?php
include '../user/core/connection.php';
include '../user/core/sessiontimeout.php';

if (!isset($_SESSION['user_id'])) {
    exit('User is not logged in.');
}

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
    <link rel="stylesheet" href="../output.css">

</head>
<body class="bg-white">

    <!-- NAV BAR -->
    <header>
        <nav class="bg-white border-b border-gray-200 dark:bg-green-900">
            <div class="max-w-screen-xl mx-auto px-4 py-6 flex items-center justify-between">
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="..\..\assets\onlicarelogo.svg" class="h-10" alt="Logo" />
                    <span class="text-2xl font-semibold whitespace-nowrap dark:text-yellow-400">OnliCare</span>
                </a>

                <!-- MENU BAR -->
                <div class="gap-8 nav-links duration-500 md:static absolute md:min-h-fit bg-green-900 max-h-[15vh] left-0 top-[-100%] md:w-auto w-full flex items-center p-5">
                    <ul class="mx-auto md:ml-0 flex flex-col items-center justify-center gap-8 md:flex-row md:gap-[2vw]">
                        <li>
                            <a href="../index.php#home" class="block py-2 px-3 text-white rounded hover:bg-transparent hover:text-green-700 dark:text-white dark:hover:text-yellow-300">Home</a>
                        </li>
                        <li>
                            <a href="../index.php#about-us" class="block py-2 px-3 rounded hover:text-green-700 dark:text-white dark:hover:text-yellow-500">About</a>
                        </li>
                        <li>
                            <a href="../index.php#services" class="block py-2 px-3 text-yellow-900 rounded hover:text-green-700 dark:text-white dark:hover:text-yellow-500">Services</a>
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
    
    <!-- CONTENT -->
    <main class="bg-gray-100 p-20 pl-24 min-h-screen">
        <div class="max-w-3xl mx-auto bg-white shadow-md rounded-md overflow-hidden">

        <!-- Profile Header -->
        <div class="bg-gray-100 px-6 py-4">
            <h1 class="text-3xl font-semibold text-gray-800">User Profile</h1>
        </div>

        <!-- Profile Information -->
        <div class="p-6">

            <!-- PHP dynamic content -->
            <?php
                $user_id = $_SESSION['user_id']; 
                $query = "SELECT * FROM user WHERE UserID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $user_id); // "i" indicates the variable type is integer

                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                $name = strtoupper($user['First_Name'] . " " . $user['Middle_Initial'] . ". " . $user['Last_Name']);
                $email = $user['Email'];
                $addressid = $user['Address_ID'];

                $address_query = "SELECT * FROM address WHERE Address_ID = ?";
                $address_stmt = $conn->prepare($address_query);
                $address_stmt->bind_param("i", $addressid); // "i" indicates the variable type is integer

                $address_stmt->execute();
                $address_result = $address_stmt->get_result();
                $address = $address_result->fetch_assoc();

                if($address_result->num_rows > 0){
                    $Full_Address = $address['Baranggay'] . ", " . $address['City'] . ", " . $address['Province'] . ", " . $address['Zip_Code'] . ", " . $address['Country'];
                } else {
                    $Full_Address = "Not Available";
                }

                

            ?>

            <div class="flex items-center space-x-4">
                <!-- User Details -->
                <div class="flex-1">
                    <!-- Display user details fetched from PHP -->
                    <h2 class="text-2xl font-semibold text-gray-800">Name</h2>
                    <p class="text-gray-600"><?php echo $name ?></p>
                    
                    <h2 class="text-2xl font-semibold text-gray-800">Email</h2>
                    <p class="text-gray-600"><?php echo $email ?></p>
                    
                    <h2 class="text-2xl font-semibold text-gray-800">Address</h2>
                    <p class="text-gray-600"><?php echo $Full_Address ?></p>
                </div>
                <div>
                    <a href="update.php?type=name" class="bg-green-800 hover:text-yellow-50 text-yellow-400 font-bold py-2 px-7 rounded mb-4 block">Update Name</a>
                    <a href="update.php?type=email" class="bg-green-800 hover:text-yellow-50 text-yellow-400 font-bold py-2 px-7 rounded mb-4 block">Update Email</a>
                    <a href="update.php?type=address" class="bg-green-800 hover:text-yellow-50 text-yellow-400 font-bold py-2 px-6 rounded block">Update Address</a>
                </div>
            </div>

        </div>

        </div>
    </main>
   

    <!-- FOOTER -->
    <footer class="bg-white shadow-md dark:bg-green-900">
        <div class="max-w-screen-xl mx-auto p-4 md:p-8 flex flex-col md:flex-row items-center justify-between">
            <a href="../index.php" class="flex items-center space-x-3 rtl:space-x-reverse mb-4 md:mb-0">
                <img src="..\..\assets\onlicarelogo.svg" class="h-8" alt="Logo">
                <span class="text-2xl font-semibold whitespace-nowrap dark:text-white">OnliCare</span>
            </a>
            <ul class="flex flex-wrap justify-center md:justify-end items-center space-x-4">
                <li>
                    <a href="../index.php#about-us" onclick="scrollToContent('about-us')" class="hover:underline text-yellow-300">About</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-yellow-200 dark:border-yellow-700">
        <span class="block text-sm text-gray-500 text-center dark:text-gray-400">© 2023 <a href="#" class="hover:underline">OnliCare™</a>. All Rights Reserved.</span>
    </footer>

    <!-- ScrollDown Script -->
    <script>
        function scrollToContent(id){
            const element = document.getElementById(id);
            element.scrollIntoView({ behavior: "smooth"})
            
        }

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
                        <a href="user/profile.php" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Profile</a>
                    </li>
                    <li>
                        <button type="submit" onclick="logout()" class="block py-2 px-3 bg-yellow-600 text-black rounded dark:text-blac dark:hover:text-white">Log out</button>
                    </li>
                `;
            }
        }

        function logout() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../user/core/logout.php", true);
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
