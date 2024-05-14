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

                $name = $user['First_Name'] . " " . $user['Middle_Initial'] . ". " . $user['Last_Name'];
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
                    $address['Baranggay'] = "Baranggay";
                    $address['City'] = "City";
                    $address['Province'] = "Province";
                    $address['Zip_Code'] = "Zip Code";
                    $address['Country'] = "Country";
                }

                

            ?>

            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <h2 class="text-2xl font-semibold text-gray-800">Name</h2>
                    <p class="text-gray-600"><?php echo $name ?></p>
                    
                    <h2 class="text-2xl font-semibold text-gray-800">Email</h2>
                    <p class="text-gray-600"><?php echo $email ?></p>
                    
                    <h2 class="text-2xl font-semibold text-gray-800">Address</h2>
                    <p class="text-gray-600"><?php echo $Full_Address ?></p>
                </div>
                <div>
                    <a onclick="showUpdate('updateName')" class="bg-green-800 hover:text-yellow-50 text-yellow-400 font-bold py-2 px-7 rounded mb-4 block cursor-pointer text-center">Update Name</a>
                    <a onclick="showUpdate('updateEmail')" class="bg-green-800 hover:text-yellow-50 text-yellow-400 font-bold py-2 px-7 rounded mb-4 block cursor-pointer text-center">Update Email</a>
                    <a onclick="showUpdate('updateAddress')" class="bg-green-800 hover:text-yellow-50 text-yellow-400 font-bold py-2 px-7 rounded mb-4 block cursor-pointer text-center">Update Address</a>
                </div>
            </div>
            
            <div id="update" class="hidden">
                <hr class="h-px my-8 bg-gray-200 border-2 dark:bg-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">

                        <div id="updateName" class="hidden">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Update Name</h2>
                                <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2 mb-2" placeholder="<?php echo $user["First_Name"] ?>"></input>
                                <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2 mb-2" placeholder="<?php echo $user["Middle_Initial"] ?>"></input>
                                <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2 mb-2" placeholder="<?php echo $user["Last_Name"] ?>"></input>

                        </div>
                        
                        <div id="updateEmail" class="hidden">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Update Email</h2>
                            <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2" placeholder="<?php echo $email ?>"></input>
                        </div>
                        
                        <div id="updateAddress" class="hidden">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Update Address</h2>
                            <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2 mb-2" placeholder="<?php echo $address['Baranggay'] ?>"></input>
                            <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2 mb-2" placeholder="<?php echo $address['City'] ?>"></input>
                            <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2 mb-2" placeholder="<?php echo $address['Province'] ?>"></input>
                            <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2 mb-2" placeholder="<?php echo $address['Zip_Code'] ?>"></input>
                            <input type="text" class="border-gray-800 border-2 rounded w-1/2 placeholder-gray-700 p-2 mb-2" placeholder="<?php echo $address['Country'] ?>"></input>
                    
                        </div>
                        
                    </div>

                    <div>
                        <a onclick="" class="bg-green-800 hover:text-yellow-50 text-yellow-400 font-bold py-2 px-7 rounded mb-4 block cursor-pointer text-center">Confirm Update</a>
                    </div>
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

        function showUpdate(update) {
            const updateContainer = document.getElementById('update');
            const sections = ['updateName', 'updateEmail', 'updateAddress'];

            // Check if the selected section is currently visible
            const selectedSection = document.getElementById(update);
            const isSelectedSectionVisible = !selectedSection.classList.contains('hidden');

            // Hide all sections initially
            sections.forEach(section => {
                document.getElementById(section).classList.add('hidden');
            });

            // Toggle the visibility of the main container based on the selected section visibility
            if (isSelectedSectionVisible) {
                // Hide the main container if the selected section is already visible
                updateContainer.classList.add('hidden');
            } else {
                // Show the selected section and the main container if the selected section is not visible
                selectedSection.classList.remove('hidden');
                updateContainer.classList.remove('hidden');
            }
        }

        function toggleVisibility(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }



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
